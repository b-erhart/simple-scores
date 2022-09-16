<?php
require_once '../vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'https://www.openligadb.de/api/']);

$response = $client->request('GET', 'getmatchdata/nfl');
$matchdayData = json_decode($response->getBody());
?>