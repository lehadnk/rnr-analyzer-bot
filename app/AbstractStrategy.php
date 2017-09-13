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

    public function __construct($payload, $metric_id) {
        $this->data = json_decode($payload);
        $this->metric_id = $metric_id;
    }

    public function setFight(Fight $fight) {
        $this->fight = $fight;
    }

    abstract function run();
}