<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/dao/MatchWeekDao.php';
require_once __DIR__ . '/dao/MatchDayMetaDao.php';
require_once '../config/config.php';

use GuzzleHttp\Client;

date_default_timezone_set('Europe/Berlin'); 

function last_word(string $multipleWords) : string {
    return array_slice(explode(' ', trim($multipleWords)), -1)[0];
}

$client = new Client(['base_uri' => 'https://api.openligadb.de/']);


$currentMatchDayResponse = $client->request('GET', 'getcurrentgroup/nfl');
$currentMatchDayOpenLigaDB = json_decode($currentMatchDayResponse->getBody());

$allMatchDaysResponse = $client->request('GET', 'getavailablegroups/nfl/2022');
$allMatchDaysOpenLigaDB = json_decode($allMatchDaysResponse->getBody());

if ($weekId == '') {
    $currentMatchesResponse = $client->request('GET', 'getmatchdata/nfl');
    $currentMatchesOpenLigaDB = json_decode($currentMatchesResponse->getBody());
} else {
    $currentMatchesResponse = $client->request('GET', 'getmatchdata/nfl/2022/' . $weekId);
    $currentMatchesOpenLigaDB = json_decode($currentMatchesResponse->getBody());
}

$currentMatchWeek = new MatchWeekDao();
$currentMatchWeek->name = '';
$currentMatchWeek->matchDays = [];

$previousTime = 0;
$matchDay = new MatchDayDao();
$matchDay->matches = [];
foreach ($currentMatchesOpenLigaDB as $matchOpenLigaDB) {
    $time = strtotime($matchOpenLigaDB->matchDateTimeUTC);

    if ($currentMatchWeek->name == '') {
        $currentMatchWeek->name = $matchOpenLigaDB->group->groupName;
        $weekId = $matchOpenLigaDB->group->groupOrderID;
    }

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
    $match->homeTeamNameShort = last_word($match->homeTeamName);
    $match->awayTeamNameShort = last_word($match->awayTeamName);
    $match->homeTeamColor = NFL_TEAM_COLORS[$match->homeTeamNameShort];
    $match->awayTeamColor = NFL_TEAM_COLORS[$match->awayTeamNameShort];
    if (!empty($matchOpenLigaDB->matchResults)) {
        $match->homeTeamScore = end($matchOpenLigaDB->matchResults)->pointsTeam1;
        $match->awayTeamScore = end($matchOpenLigaDB->matchResults)->pointsTeam2;
    }
    // TODO: add correct link generation for post-season matches
    $match->detailsLink = 'https://nfl.com/games/' . $match->awayTeamNameShort . '-at-' . $match->homeTeamNameShort . '-' . last_word($matchOpenLigaDB->leagueName) . '-reg-' . last_word($matchOpenLigaDB->group->groupName);

    array_push($matchDay->matches, $match);
}

array_push($currentMatchWeek->matchDays, $matchDay);

$allMatchDaysMeta = [];
foreach ($allMatchDaysOpenLigaDB as $matchDayOpenLigaDB) {
    if ($matchDayOpenLigaDB->groupName == '') {
        break;
    }

    $matchDay = new MatchDayMetaDao();
    $matchDay->name = $matchDayOpenLigaDB->groupName;
    $matchDay->id = $matchDayOpenLigaDB->groupOrderID;
    $matchDay->link = '/nfl/' . $matchDay->id;

    array_push($allMatchDaysMeta, $matchDay);
}

include('../templates/nfl.php');
?>