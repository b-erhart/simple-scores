<?php
namespace SimpleScores\OpenLigaDbApi;

class MatchweekMetaArray {
    private array $matchweekMetas;

    public function __construct() {
        $this->matchweekMetas = [];
    }

    public function add(MatchweekMeta $matchweekMeta) : void {
        array_push($this->matchweekMetas, $matchweekMeta);
    }

    public function getArray() : array {
        return $this->matchweekMetas;
    }
}
?>