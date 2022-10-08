<?php
use SimpleScores\OpenLigaDbApi\OpenLigaDbApiClient;

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

const LEAGUE_NFL = 'nfl';
const SEASON = '2022';

$openLigaDbClient = new OpenLigaDbApiClient();

$overallStandings = $openLigaDbClient->getStandings(LEAGUE_NFL, SEASON, NFL_TEAM_COLORS)->getArray();
$overallMappedStandings = [];

foreach ($overallStandings as $standing) {
    $overallMappedStandings[$standing->team] = $standing;
}

$divisions = [];
foreach (NFL_TEAM_DIVISIONS as $divisionName => $divisionTeamNames) {
    $entries = [];

    foreach ($divisionTeamNames as $teamName) {
        array_push($entries, $overallMappedStandings[$teamName]);
    }

    usort($entries, 'sort_standings');

    $divisions[$divisionName] = $entries;
}
?>