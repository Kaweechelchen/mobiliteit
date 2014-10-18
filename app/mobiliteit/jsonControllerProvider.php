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

            $ctr->get( '/', function( Application $app ) {

                return 'you need to provide a station id. Example: <a href="https://mobiliteit.herokuapp.com/api/1/200405036">https://mobiliteit.herokuapp.com/api/1/200405036</a>';

            });

            $ctr->get( '/{stationId}/{limit}', function( Application $app, $stationId, $limit ) {

                $data = self::mobilityData(
                    $app,
                    $stationId,
                    $limit
                );

                $busses[ 'stationName' ] = html_entity_decode ( $data[ 'stationName' ] );

                foreach ($data[ 'journey' ] as $journey ) {

                    unset( $bus );

                    $date = 20  . substr( $journey[ 'da' ], 6, 2)
                        . '-' . substr( $journey[ 'da' ], 3, 2)
                        . '-' . substr( $journey[ 'da' ], 0, 2);

                    if ( $journey[ 'rt' ] ) {

                        $time = $journey[ 'rt' ][ 'dlt' ];
                        $delay = strtotime(
                            $date . ' ' . $time
                        ) - strtotime(
                            $date . ' ' . $journey[ 'ti' ]
                        );

                    } else {

                        $time = $journey[ 'ti' ];
                        $delay = 0;

                    }

                    $bus[ 'line' ] = (int) $journey[ 'ln' ];
                    $bus[ 'destination' ] = html_entity_decode ( $journey[ 'st' ] );
                    $bus[ 'departure' ] = strtotime(
                        $date . ' ' . $time
                    );
                    $bus[ 'delay' ] = $delay;

                    $bussesArray[ $bus[ 'timestamp' ] ][] = $bus;

                }

                ksort( $bussesArray );

                foreach ( $bussesArray as $busesInArray ) {

                    foreach ( $busesInArray as $bus ) {

                        $busses[ 'journeys' ][] = $bus;

                    }

                }

                return $app->json( $busses );

            })->value('limit', 10);

            return $ctr;

        }

    }
