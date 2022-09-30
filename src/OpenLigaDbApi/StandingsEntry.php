<?php
namespace SimpleScores\OpenLigaDbApi;

class StandingsEntry {
    public string $team;
    public string $teamColor;
    public string $matches;
    public string $record;
    public string $wins;
    public string $losses;
    public string $ties;
    public string $points;
    public int $netPoints;
    public int $tablePoints;
    public bool $divisionRivalHasEqualRecord = false;
}
?>