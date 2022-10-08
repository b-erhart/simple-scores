<?php
use SimpleScores\OpenLigaDbApi\OpenLigaDbApiClient;
use SimpleScores\OpenLigaDbApi\DetailsLinkType;

const LEAGUE_NFL = 'nfl';
const SEASON = '2022';

try {
    $openLigaDbClient = new OpenLigaDbApiClient();

    $currentMatchweekInSeasonId = $openLigaDbClient->getCurrentMatchweekMeta(LEAGUE_NFL)->getInSeasonId();

    $allMatchweekMetas = $openLigaDbClient->getAllMatchweekMetas(LEAGUE_NFL, SEASON)->getArray();

    if ($selectedWeek == '') {
        $matchweek = $openLigaDbClient->getCurrentMatchweek(LEAGUE_NFL, SEASON, NFL_TEAM_COLORS, DetailsLinkType::Nfl);
        $selectedWeek = $matchweek->getInSeasonId();
    }
    else {
        $matchweek = $openLigaDbClient->getMatchweek(LEAGUE_NFL, SEASON, (int) $selectedWeek, NFL_TEAM_COLORS, DetailsLinkType::Nfl);
    }

    $dataRetrievalSuccessful = true;
}
catch (GuzzleHttp\Exception\ConnectException $webServiceException) {
    $dataRetrievalSuccessful = false;
}
?>