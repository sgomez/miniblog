<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new ValidatorServiceProvider());

// Twig
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    'twig.options' => array('cache' => __DIR__.'/../cache'),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...
	$twig->addExtension(new Twig_Extensions_Extension_Text($app));

    return $twig;
}));

// Doctrine
$app->register(new DoctrineServiceProvider());
$app['db.options'] = array(
    'driver'   => 'pdo_sqlite',
    'path'     => __DIR__.'/../config/schema.sqlite',
);

// HTTP Cache
$app->register(new HttpCacheServiceProvider(), array(
   'http_cache.cache_dir' => __DIR__.'/../cache/http',
   'http_cache.esi'       => null,
));

// Firewall
$app->register(new SecurityServiceProvider());
$app['security.encoder.digest'] = $app->share(function ($app) {
    // algoritmo SHA-1, con 1 iteración y sin codificar en base64
    return new MessageDigestPasswordEncoder('sha1', false, 1);
});

$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/admin',
        'http'    => true,
        'users'   => array(
            // user: admin; password: 1234
            'admin' => array('ROLE_ADMIN', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
        ),
    ),
);

return $app;
