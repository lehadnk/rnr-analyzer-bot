<?php
/**
 * AbstractStrategy.php
 * Creator: lehadnk
 * Date: 13/09/2017
 */

namespace App;


abstract class AbstractStrategy {
    protected $data;
    /**
     * @var Fight
     */
    protected $fight;
    protected $metric_id;
    /**
     * @var BossMetric
     */
    protected $metric;

    public function __construct(BossMetric $metric) {
        $this->data = json_decode($metric->payload);
        $this->metric_id = $metric->id;
        $this->metric = $metric;
    }

    public function setFight(Fight $fight) {
        $this->fight = $fight;
    }

    abstract function run();

    abstract function getReport($raidDate);

    abstract function getPlayerDetailedReport($raidDate, $playerName);
}