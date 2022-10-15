<?php
namespace SimpleScores\OpenLigaDbApi;

use GuzzleHttp\Client;

date_default_timezone_set('Europe/Berlin'); 

function last_word(string $multipleWords) : string {
    return array_slice(explode(' ', trim($multipleWords)), -1)[0];
}

abstract class DetailsLinkType {
    const Nfl = 0;
    const Bundesliga = 1;
}

class OpenLigaDbApiClient {
    private const BASE_URL = 'https://api.openligadb.de/';
    private Client $httpClient;

    public function __construct() {
        $this->httpClient = new Client(['base_uri' => self::BASE_URL]);
    }

    public function getAllMatchweekMetas(string $league, string $season) : MatchweekMetaArray {
        $apiResponseArray = json_decode($this->httpClient->request('GET', 'getavailablegroups/' . $league . '/' . $season)->getBody());
        $allMatchweekMetas = new MatchweekMetaArray();

        foreach ($apiResponseArray as $apiResponse) {
            if ($apiResponse->groupName != '') {
                $allMatchweekMetas->add($this->apiResponseToMatchweekMeta($apiResponse));
            }
        }

        return $allMatchweekMetas;
    }

    public function getCurrentMatchweekMeta(string $league) : MatchweekMeta {
        $apiResponse = json_decode($this->httpClient->request('GET', 'getcurrentgroup/' . $league)->getBody());
        
        return $this->apiResponseToMatchweekMeta($apiResponse);
    }

    public function getMatchweek(string $league, string $season, int $inSeasonId, array $teamColors, int $detailsLinkType) : Matchweek {
        $apiResponseArray = json_decode($this->httpClient->request('GET', 'getmatchdata/' . $league . '/' . $season . '/' . $inSeasonId)->getBody());

        return $this->apiResponseArrayToMatchweek($apiResponseArray, $teamColors, $detailsLinkType);
    }

    public function getCurrentMatchweek(string $league, string $season, array $teamColors, int $detailsLinkType) : Matchweek {
        $currentMatchweekMeta = $this->getCurrentMatchweekMeta($league);
        return $this->getMatchweek($league, $season, $currentMatchweekMeta->getInSeasonId(), $teamColors, $detailsLinkType);
    }

    public function getStandings(string $league, string $season, array $teamColors) : StandingsEntryArray {
        $apiResponseArray = json_decode($this->httpClient->request('GET', 'getbltable/' . $league . '/' . $season)->getBody());

        return $this->apiResponseArrayToStandingsEntryArray($apiResponseArray, $teamColors);
    }

    private function apiResponseToMatchweekMeta($apiResponse) : MatchweekMeta {
        return new MatchweekMeta(
            $apiResponse->groupName,
            $apiResponse->groupOrderID,
            $apiResponse->groupID
        );
    }

    private function apiResponseArrayToMatchweek($apiResponseArray, array $teamColors, int $detailsLinkType) : Matchweek {
        $matchweek = new Matchweek();

        $firstIteration = true;
        $previousDateTime = 0;
        $matchday = new Matchday();
        foreach ($apiResponseArray as $apiResponse) {
            $dateTime = strtotime($apiResponse->matchDateTimeUTC);

            if ($firstIteration) {
                $matchweek->setName($apiResponse->group->groupName);
                $matchweek->setInSeasonId($apiResponse->group->groupOrderID);
                $matchweek->setId($apiResponse->group->groupID);
                $firstIteration = false;
            }

            if (date('l, F jS Y', $previousDateTime) != date('l, F jS Y', $dateTime)) {
                if ($previousDateTime != 0) {
                    $matchweek->addMatchday($matchday);
                    $matchday = new Matchday();
                }

                $matchday->setDate(date('l, F jS Y', $dateTime));
                $previousDateTime = $dateTime;
            }

            $matchup = new Matchup();
            $matchup->isLive = !$apiResponse->matchIsFinished && !empty($apiResponse->matchResults);
            $matchup->isFinished = $apiResponse->matchIsFinished;
            $matchup->time = date('H:i T', $dateTime);
            $matchup->homeTeamName = $apiResponse->team1->teamName;
            $matchup->awayTeamName = $apiResponse->team2->teamName;
            $matchup->homeTeamNameShort = ($apiResponse->team1->shortName != '') ? $apiResponse->team1->shortName : last_word($matchup->homeTeamName);
            $matchup->awayTeamNameShort = ($apiResponse->team2->shortName != '') ? $apiResponse->team2->shortName : last_word($matchup->awayTeamName);
            $matchup->homeTeamColor = $teamColors[$matchup->homeTeamNameShort];
            $matchup->awayTeamColor = $teamColors[$matchup->awayTeamNameShort];
            if (!empty($apiResponse->matchResults) || $matchup->isLive) {
                $matchup->homeTeamScore = reset($apiResponse->matchResults)->pointsTeam1;
                $matchup->awayTeamScore = reset($apiResponse->matchResults)->pointsTeam2;
            }
            $matchup->detailsLink = $this->generateDetailsLink($detailsLinkType, [
                'inSeasonId' => $matchweek->getInSeasonId(),
                'homeTeam' => $matchup->homeTeamNameShort,
                'awayTeam' => $matchup->awayTeamNameShort,
                'year' => last_word($apiResponse->leagueName)
            ]);

            $matchday->addMatchup($matchup);
        }

        $matchweek->addMatchday($matchday);

        return $matchweek;
    }

    private function apiResponseArrayToStandingsEntryArray($apiResponseArray, array $teamColors) : StandingsEntryArray {
        $standingsEntries = new StandingsEntryArray();

        foreach ($apiResponseArray as $apiResponse) {
            $standingsEntry = new StandingsEntry();

            $standingsEntry->team = ($apiResponse->shortName != '') ? $apiResponse->shortName : last_word($apiResponse->teamName);
            $standingsEntry->teamColor = $teamColors[$standingsEntry->team];
            $standingsEntry->matches = $apiResponse->matches;
            $standingsEntry->record = $apiResponse->won . '-' . $apiResponse->lost . (($apiResponse->draw != 0) ? '-' . $apiResponse->draw : '');
            $standingsEntry->wins = $apiResponse->won;
            $standingsEntry->losses = $apiResponse->lost;
            $standingsEntry->ties = $apiResponse->draw;
            $standingsEntry->pointsFor = $apiResponse->goals;
            $standingsEntry->pointsAgainst = $apiResponse->opponentGoals;
            $standingsEntry->netPoints = $apiResponse->goalDiff;
            $standingsEntry->tablePoints = $apiResponse->points;
        
            $standingsEntries->add($standingsEntry);
        }

        return $standingsEntries;
    }

    private function generateDetailsLink(int $detailsLinkType, array $parameters) : string {
        switch ($detailsLinkType) {
            case DetailsLinkType::Nfl:
                return $this->generateNflDetailsLink($parameters);
            case DetailsLinkType::Bundesliga:
                return $this->generateBundesligaDetailsLink($parameters);
        }

        return "";
    }

    private function generateNflDetailsLink(array $parameters) : string {
        $matchupTypePrefix = 'reg';
        $matchupId = $parameters['inSeasonId'];

        if ($matchupId > 18) {
            $matchupTypePrefix = 'post';
            $matchupId = $matchupId - 18;
        }

        return 'https://nfl.com/games/' . $parameters['awayTeam'] . '-at-' . $parameters['homeTeam'] . '-' . $parameters['year'] . '-' . $matchupTypePrefix . '-' . $matchupId;
    }

    private function generateBundesligaDetailsLink(array $parameters) : string {
        $linkSeason = str_replace('/', '-', $parameters['year']);

        return 'https://www.bundesliga.com/en/bundesliga/matchday/' . $linkSeason . '/' . $parameters['inSeasonId'] . '/' . BL_TEAM_LINK_NAMES[$parameters['homeTeam']] . '-vs-' . BL_TEAM_LINK_NAMES[$parameters['awayTeam']] . '/liveticker';
    }
}
?>