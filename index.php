<?php

date_default_timezone_set("America/Sao_paulo");

require_once __DIR__.'/vendor/autoload.php';

use SearchCode\AdapterManager;
use Symfony\Component\HttpFoundation\Request;
use SearchCode\Exception\AdapterException;

$app = new Silex\Application();

$app->error(function (\Exception $e, Request $request, $code) use ($app){
    return $app->json(array('message'=>  $e->getMessage()));
});

$app->error(function (AdapterException $e, Request $request, $code) use ($app) {
    return $app->json(array('message'=> $e->getMessage()));
});

$app->get('/search/', function(Request $request) use($app) {
    $adapter = AdapterManager::getAdapter(getenv('SEARCH_CODE_ADAPTER'));
    $data    = $adapter->parseRequest($request)->searchCode();

    return $app->json($data);
});

$app->run();