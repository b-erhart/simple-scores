<?php
namespace SimpleScores\OpenLigaDbApi;

class StandingsEntryArray {
    private array $standingsEntries;

    public function __construct() {
        $this->standingsEntries = [];
    }

    public function add(StandingsEntry $standingsEntry) : void {
        array_push($this->standingsEntries, $standingsEntry);
    }

    public function getArray() : array {
        return $this->standingsEntries;
    }
}
?>