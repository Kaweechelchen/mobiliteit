<?php

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    require_once __DIR__.'/bootstrap.php';

    $app = new Silex\Application();

    $app[ 'debug' ] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    $app->mount( '/api/1/', new mobiliteit\jsonControllerProvider() );

    $app->get('/api', function () use ( $app ) {
        return $app->redirect('/api/1/');
    });

    $app->get('/', function () use ( $app ) {
        return $app['twig']->render( 'getStation.html' );
    });

    $app->after(function (Request $request, Response $response) {

        $response->headers->set('Access-Control-Allow-Origin', '*');

    });

    return $app;
