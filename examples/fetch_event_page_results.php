<?php

require '../vendor/autoload.php';

$parameters = [
    'eventSlug' => '2020/parang',
    'categorySlug' => 'C1M',
];

$client = new \Sportic\Omniresult\Timeit\TimeitClient();
$parser = $client->results($parameters);
$data   = $parser->getContent();

var_dump($data->all());
