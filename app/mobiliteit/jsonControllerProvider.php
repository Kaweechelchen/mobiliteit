<?php

    namespace mobiliteit;

    use Silex\Application;
    use Silex\ControllerProviderInterface;

    class jsonControllerProvider implements ControllerProviderInterface {

        static public function busses ( $app, $stationId, $limit ) {

            $mobilityData = file_get_contents( 'http://travelplanner.mobiliteit.lu/hafas/cdt/stboard.exe/en?L=vs_stb&input=' . $stationId . '&boardType=dep&time=' . date( "H:i" ) . '&selectDate=today&start=yes&requestType=0&maxJourneys=' . $limit ) ;

            $mobilityData = json_decode(
                substr( $mobilityData, 14 )
            );

            return $app->json( $mobilityData );

        }

        public function connect( Application $app ) {

            $ctr = $app['controllers_factory'];

            $ctr->get( '/', function( Application $app ) {

                return 'you need to provide a twitter handle. Example: <a href="https://twjs.herokuapp.com/syn2cat">https://twjs.herokuapp.com/syn2cat</a>';

            });

            $ctr->get( '/{stationId}/{limit}', function( Application $app, $stationId, $limit ) {

                return self::busses( $app, $stationId, $limit );

            })->value('limit', 10);

            return $ctr;

        }

    }
