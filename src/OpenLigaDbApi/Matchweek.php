<?php
namespace SimpleScores\OpenLigaDbApi;

class Matchweek {
    private string $name;
    private int $inSeasonId;
    private int $id;
    private array $matchdays;

    public function __construct() {
        $this->matchdays = [];
    }

    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function getName() : string {
        return $this->name;
    }

    public function setInSeasonId(int $inSeasonId) : void {
        $this->inSeasonId = $inSeasonId;
    }

    public function getInSeasonId() : int {
        return $this->inSeasonId;
    }

    public function setId(int $id) : void {
        $this->id = $id;
    }

    public function getId() : int {
        return $this->id;
    }

    public function addMatchday(Matchday $matchday) : void {
        array_push($this->matchdays, $matchday);
    }

    public function getMatchdays() : array {
        return $this->matchdays;
    }
}
?>