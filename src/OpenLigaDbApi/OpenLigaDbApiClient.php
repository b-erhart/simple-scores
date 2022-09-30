<?php
namespace SimpleScores\OpenLigaDbApi;

use GuzzleHttp\Client;

date_default_timezone_set('Europe/Berlin'); 

function last_word(string $multipleWords) : string {
    return array_slice(explode(' ', trim($multipleWords)), -1)[0];
}

enum DetailsLinkType {
    case Nfl;
    case Bundesliga;
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

    public function getMatchweek(string $league, string $season, int $inSeasonId, array $teamColors, DetailsLinkType $detailsLinkType) : Matchweek {
        $apiResponseArray = json_decode($this->httpClient->request('GET', 'getmatchdata/' . $league . '/' . $season . '/' . $inSeasonId)->getBody());

        return $this->apiResponseArrayToMatchweek($apiResponseArray, $teamColors, $detailsLinkType);
    }

    public function getCurrentMatchweek(string $league, array $teamColors, DetailsLinkType $detailsLinkType) : Matchweek {
        $apiResponseArray = json_decode($this->httpClient->request('GET', 'getmatchdata/' . $league)->getBody());

        return $this->apiResponseArrayToMatchweek($apiResponseArray, $teamColors, $detailsLinkType);
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

    private function apiResponseArrayToMatchweek($apiResponseArray, array $teamColors, DetailsLinkType $detailsLinkType) : Matchweek {
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

            if (date('l, j. F Y', $previousDateTime) != date('l, j. F Y', $dateTime)) {
                if ($previousDateTime != 0) {
                    $matchweek->addMatchday($matchday);
                    $matchday = new Matchday();
                }

                $matchday->setDate(date('l, j. F Y', $dateTime));
                $previousDateTime = $dateTime;
            }

            $matchup = new Matchup();
            $matchup->isFinished = $apiResponse->matchIsFinished;
            $matchup->time = date('H:i T', $dateTime);
            $matchup->homeTeamName = $apiResponse->team1->teamName;
            $matchup->awayTeamName = $apiResponse->team2->teamName;
            $matchup->homeTeamNameShort = ($apiResponse->team1->shortName != '') ? $apiResponse->team1->shortName : last_word($matchup->homeTeamName);
            $matchup->awayTeamNameShort = ($apiResponse->team2->shortName != '') ? $apiResponse->team2->shortName : last_word($matchup->awayTeamName);
            $matchup->homeTeamColor = $teamColors[$matchup->homeTeamNameShort];
            $matchup->awayTeamColor = $teamColors[$matchup->awayTeamNameShort];
            if (!empty($apiResponse->matchResults)) {
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

    private function generateDetailsLink(DetailsLinkType $detailsLinkType, array $parameters) : string {
        return match($detailsLinkType) {
            DetailsLinkType::Nfl => $this->generateNflDetailsLink($parameters),
            DetailsLinkType::Bundesliga => $this->generateBundesligaDetailsLink($parameters)
        };
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

    private function generateBundesligaDetailsLink($parameters) : string {
        return '';
    }
}
?>