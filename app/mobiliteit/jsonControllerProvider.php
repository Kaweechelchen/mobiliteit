<?php

    // Thierry Degeling [@Kaweechelchen]

    namespace mobiliteit;

    use Silex\Application;
    use Silex\ControllerProviderInterface;

    class jsonControllerProvider implements ControllerProviderInterface {

        /**
         * This function returns an array of
         * @param  [type] $app       Silex Application
         * @param  [type] $stationId the id of a busStation
         * @param  [type] $limit     The limit of results you want to get back
         * @return [type]            Array of bus journeys
         */
        static public function mobilityData ( $app, $stationId, $limit ) {

            // Getting the json from Mobiliteit.lu
            // [input] is the id of the station you want to get bus departures for
            // [time] is the time of the day you want the earliest departure for
            // [maxjourneys] is the amount of journeys you want to get
            $mobilityData = file_get_contents(
                'http://travelplanner.mobiliteit.lu/'
                . 'hafas/cdt/stboard.exe/en?L=vs_stb'
                . '&start=yes'
                . '&requestType=0'
                . '&input=' . $stationId
                . '&time=' . date( "H:i" )
                . '&maxJourneys=' . $limit );

            // Removing the random string in front of the json which
            // mobiliteit.lu returns
            $mobilityData = substr( $mobilityData, 14 );

            // Converting the json to an array
            // you need to pass true as 2nd parameter if you want to avoid
            // stdClass in the array
            $mobilityData = json_decode(
                $mobilityData,
                true
            );

            // returning the array
            return $mobilityData;

        }

        public function connect( Application $app ) {

            $ctr = $app['controllers_factory'];

            /**
             * Called up the API url but didn't pass a parameter for the station ID...
             * Let the code explain you that you need to do that ;)
             */
            $ctr->get( '/', function( Application $app ) {

                return 'you need to provide a station id. Example: <a href="https://mobiliteit.herokuapp.com/api/1/200405036">https://mobiliteit.herokuapp.com/api/1/200405036</a>';

            });

            /**
             * Return some JSON in case a bus station as been passed as a parameter
             */
            $ctr->get( '/{stationId}/{limit}', function( Application $app, $stationId, $limit ) {

                /**
                 * Get an array of journeys for a bus station
                 */
                $data = self::mobilityData(
                    $app,
                    $stationId,
                    $limit
                );

                // Retrun the name of the Station
                // getting rid of the html entities first though
                $busses[ 'stationName' ] = html_entity_decode ( $data[ 'stationName' ] );

                /**
                 * Loop through the journeys and refactor the information
                 */
                foreach ($data[ 'journey' ] as $journey ) {

                    // Getting rid of leftover information
                    unset( $bus );

                    // Format the date delivered from the mobiliteit API
                    $date = 20  . substr( $journey[ 'da' ], 6, 2)
                        . '-' . substr( $journey[ 'da' ], 3, 2)
                        . '-' . substr( $journey[ 'da' ], 0, 2);

                    // Check if there's delay on the line
                    if ( $journey[ 'rt' ] ) {

                        // If there is delay, replace the departure time by the
                        // time the bus is actually going to leave.
                        $time = $journey[ 'rt' ][ 'dlt' ];

                        // We'll also set the delay information to the delay the
                        // bus is experiencing in seconds
                        $delay = strtotime( $date . ' ' . $time )
                               - strtotime( $date . ' ' . $journey[ 'ti' ] );

                    } else {

                        // If there's no delay, just return the departure time
                        // from mobiliteit.lu
                        $time = $journey[ 'ti' ];
                        $delay = 0;

                    }

                    // Extracting the bus line number
                    $bus[ 'line' ] = (int) $journey[ 'ln' ];

                    // Extracting the name of the destination station
                    $bus[ 'destination' ] = html_entity_decode ( $journey[ 'st' ] );

                    // Converting the date and time of departure to a unix
                    // timestamp
                    $bus[ 'departure' ] = strtotime( $date . ' ' . $time );

                    // returning the delay
                    $bus[ 'delay' ] = $delay;

                    // Add this bus journey to the array we are going to return
                    // Note that the key of the entries is the departure time
                    // We are going to use this to sort busses by dep. time
                    // We are adding a level to the array because we might have
                    // multiple busses departing at the same time
                    $bussesArray[ $bus[ 'departure' ] ][] = $bus;

                }

                // Sorting the busses by their departure tim
                ksort( $bussesArray );

                // loop through all the bus departures
                foreach ( $bussesArray as $busesInArray ) {

                    // We might get 2 busses at the same time, we need to get
                    // them to the same level now
                    foreach ( $busesInArray as $bus ) {

                        // Add every journey to the journey object we are going
                        // to return"
                        $busses[ 'journeys' ][] = $bus;

                    }

                }

                // return the $busses variable containing all of the information
                // we just gathered
                return $app->json( $busses );

                // setting a default value for limit if none was set
            })->value('limit', 10);

            // return the controller
            return $ctr;

        }

    }
