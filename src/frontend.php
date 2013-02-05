<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Controladores relacionados con la parte de administración del sitio web
$frontend = $app['controllers_factory'];

// MAIN -----------------------------------------------------------------
$frontend->get('/', function () use ($app) {
    $entries = $app['db']->fetchAll("SELECT * FROM entry");

    return $app['twig']->render('frontend/main.twig', array(
        'entries' => $entries
    ));
})
->bind('homepage');

return $frontend;
