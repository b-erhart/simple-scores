<?php
require_once '../vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'https://www.openligadb.de/api/']);

$currentMatchDayResponse = $client->request('GET', 'getcurrentgroup/nfl');
$currentMatchDay = json_decode($currentMatchDayResponse->getBody());

$allMatchDaysResponse = $client->request('GET', 'getavailablegroups/nfl/2022');
$allMatchDays = json_decode($allMatchDaysResponse->getBody());

$currentMatchesResponse = $client->request('GET', 'getmatchdata/nfl');
$currentMatches = json_decode($currentMatchesResponse->getBody());

include('../templates/nfl.php');
?>