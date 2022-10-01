<?php
use SimpleScores\OpenLigaDbApi\OpenLigaDbApiClient;
use SimpleScores\OpenLigaDbApi\DetailsLinkType;

const LEAGUE_BL = 'bl1';
const SEASON = '2022';

$openLigaDbClient = new OpenLigaDbApiClient();

$currentMatchweekInSeasonId = $openLigaDbClient->getCurrentMatchweekMeta(LEAGUE_BL)->getInSeasonId();

$allMatchweekMetas = $openLigaDbClient->getAllMatchweekMetas(LEAGUE_BL, SEASON)->getArray();

if ($selectedWeek == '') {
    $matchweek = $openLigaDbClient->getCurrentMatchweek(LEAGUE_BL, SEASON, BL_TEAM_COLORS, DetailsLinkType::Bundesliga);
    $selectedWeek = $matchweek->getInSeasonId();
}
else {
    $matchweek = $openLigaDbClient->getMatchweek(LEAGUE_BL, SEASON, (int) $selectedWeek, BL_TEAM_COLORS, DetailsLinkType::Bundesliga);
}
?>