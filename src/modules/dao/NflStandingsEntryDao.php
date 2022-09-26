<?php
class NflStandingsEntryDao {
    public string $team;
    public string $teamColor;
    public string $record;
    public string $wins;
    public string $losses;
    public string $ties;
    public string $points;
    public int $netPoints;
    public bool $divisionRivalHasEqualRecord = false;
}
?>