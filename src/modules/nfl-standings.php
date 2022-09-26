<?php
require_once __DIR__ . '/dao/NflStandingsDivisionDao.php';
require_once '../config.php';

use GuzzleHttp\Client;

date_default_timezone_set('Europe/Berlin'); 

function last_word(string $multipleWords) : string {
    return array_slice(explode(' ', trim($multipleWords)), -1)[0];
}

function sort_standings($a, $b) : int {
    if ($a->wins > $b->wins) {
        return -1;
    }
    else if ($a->wins < $b->wins) {
        return 1;
    }
    else if ($a->losses < $b->losses) {
        return -1;
    }
    else if ($a->losses > $b->losses) {
        return 1;
    }
    else if ($a->ties > $b->ties) {
        return -1;
    }
    else if ($a->ties < $b->ties) {
        return 1;
    }
    
    $a->divisionRivalHasEqualRecord = true;
    $b->divisionRivalHasEqualRecord = true;

    if ($a->netPoints > $b->netPoints) {
        return -1;
    }
    else if ($a->netPoints < $b->netPoints) {
        return 1;
    }

    return 0;
}

$client = new Client(['base_uri' => 'https://api.openligadb.de/']);

$standingsResponse = $client->request('GET', 'getbltable/nfl/2022');
$standingsOpenLigaDB = json_decode($standingsResponse->getBody());

$allStandings = [];
foreach ($standingsOpenLigaDB as $standingOpenLigaDB) {
    $standingsEntry = new NflStandingsEntryDao();
    $standingsEntry->team = last_word($standingOpenLigaDB->teamName);
    $standingsEntry->teamColor = NFL_TEAM_COLORS[$standingsEntry->team];
    $standingsEntry->record = $standingOpenLigaDB->won . '-' . $standingOpenLigaDB->lost . (($standingOpenLigaDB->draw != 0) ? '-' . $standingOpenLigaDB->draw : '');
    $standingsEntry->wins = $standingOpenLigaDB->won;
    $standingsEntry->losses = $standingOpenLigaDB->lost;
    $standingsEntry->ties = $standingOpenLigaDB->draw;
    $standingsEntry->points = $standingOpenLigaDB->goals . '-' . $standingOpenLigaDB->opponentGoals;
    $standingsEntry->netPoints = $standingOpenLigaDB->goalDiff;

    $allStandings[$standingsEntry->team] = $standingsEntry;
}

$divisions = [];
foreach (NFL_TEAM_DIVISIONS as $divisionName => $divisionTeamNames) {
    $division = new NflStandingsDivisionDao();
    $division->name = $divisionName;
    $entries = [];

    foreach ($divisionTeamNames as $teamName) {
        array_push($entries, $allStandings[$teamName]);
    }

    usort($entries, 'sort_standings');

    $division->entries = $entries;

    array_push($divisions, $division);
}

include('../templates/nfl-standings.php');
?>