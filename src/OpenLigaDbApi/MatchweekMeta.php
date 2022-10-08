<?php
namespace SimpleScores\OpenLigaDbApi;

class MatchweekMeta {
    private string $name;
    private int $inSeasonId;
    private int $id;

    public function __construct(string $name, int $inSeasonId, int $id) {
        $this->name = $name;
        $this->inSeasonId = $inSeasonId;
        $this->id = $id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getInSeasonId() : int {
        return $this->inSeasonId;
    }

    public function getId() : int {
        return $this->id;
    }
}
?>