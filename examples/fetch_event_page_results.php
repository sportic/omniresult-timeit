<?php

require '../vendor/autoload.php';

$parameters = [
    'eventSlug' => 'cozia-mountain-run-6',
    'page' => 7,
];

$client = new \Sportic\Omniresult\Timeit\TrackmyraceClient();
$resultsParser = $client->results($parameters);
$resultsData   = $resultsParser->getContent();

var_dump($resultsData);
