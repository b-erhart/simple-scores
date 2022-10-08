<?php
namespace SimpleScores\OpenLigaDbApi;

class Matchday {
    private string $date;
    private array $matchups;

    public function __construct() {
        $this->matchups = [];
    }

    public function setDate(string $date) : void {
        $this->date = $date;
    }

    public function getDate() : string {
        return $this->date;
    }

    public function addMatchup(Matchup $matchup) : void {
        array_push($this->matchups, $matchup);
    }

    public function getMatchups() : array {
        return $this->matchups;
    }
}
?>