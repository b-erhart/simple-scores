<?php
use SimpleScores\OpenLigaDbApi\OpenLigaDbApiClient;

const LEAGUE_BL = 'bl1';
const SEASON = '2022';

$openLigaDbClient = new OpenLigaDbApiClient();

$standings = $openLigaDbClient->getStandings(LEAGUE_BL, SEASON, BL_TEAM_COLORS)->getArray();
?>