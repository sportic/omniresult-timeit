<?php

require '../vendor/autoload.php';

$parameters = [
    'eventSlug' => '2020/parang',
];

$client = new \Sportic\Omniresult\Timeit\TimeitClient();
$parser = $client->event($parameters);
$data   = $parser->getContent();

var_dump($data->all());
