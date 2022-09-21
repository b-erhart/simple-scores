<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . './dao/MatchWeekDao.php';
require_once '../config.php';

use GuzzleHttp\Client;

date_default_timezone_set('Europe/Berlin'); 

$client = new Client(['base_uri' => 'https://api.openligadb.de/']);

$currentMatchDayResponse = $client->request('GET', 'getcurrentgroup/nfl');
$currentMatchDayOpenLigaDB = json_decode($currentMatchDayResponse->getBody());

// $allMatchDaysResponse = $client->request('GET', 'getavailablegroups/nfl/2022');
// $allMatchDays = json_decode($allMatchDaysResponse->getBody());

$currentMatchesResponse = $client->request('GET', 'getmatchdata/nfl');
$currentMatchesOpenLigaDB = json_decode($currentMatchesResponse->getBody());

$currentMatchWeek = new MatchWeekDao();
$currentMatchWeek->name = $currentMatchDayOpenLigaDB->groupName;
$currentMatchWeek->matchDays = [];

$previousTime = 0;
$matchDay = new MatchDayDao();
$matchDay->matches = [];
foreach ($currentMatchesOpenLigaDB as $matchOpenLigaDB) {
    $time = strtotime($matchOpenLigaDB->matchDateTimeUTC);

    if (date('l, j. F Y', $previousTime) != date('l, j. F Y', $time)) {
        if ($previousTime != 0) {
            array_push($currentMatchWeek->matchDays, $matchDay);
            $matchDay = new MatchDayDao();
            $matchDay->matches = [];
        }

        $matchDay->date = date('l, j. F Y', $time);
        $previousTime = $time;
    }

    $match = new MatchDao();
    $match->isFinished = $matchOpenLigaDB->matchIsFinished;
    $match->time = date('H:i T', $time);
    $match->homeTeamName = $matchOpenLigaDB->team1->teamName;
    $match->awayTeamName = $matchOpenLigaDB->team2->teamName;
    $match->homeTeamNameShort = array_slice(explode(' ', trim($match->homeTeamName)), -1)[0];
    $match->awayTeamNameShort = array_slice(explode(' ', trim($match->awayTeamName)), -1)[0];
    $match->homeTeamColor = NFL_TEAM_COLORS[$match->homeTeamNameShort];
    $match->awayTeamColor = NFL_TEAM_COLORS[$match->awayTeamNameShort];
    if (!empty($matchOpenLigaDB->matchResults)) {
        $match->homeTeamScore = end($matchOpenLigaDB->matchResults)->pointsTeam1;
        $match->awayTeamScore = end($matchOpenLigaDB->matchResults)->pointsTeam2;
    }

    array_push($matchDay->matches, $match);
}

include('../templates/nfl.php');
?>