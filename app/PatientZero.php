<?php
/**
 * PatientZero.php
 * Creator: lehadnk
 * Date: 13/09/2017
 */

namespace App;


class PatientZero extends AbstractStrategy
{
    public function run() {
        $url = "https://www.warcraftlogs.com/reports/events/damage-taken/{$this->fight->log_id}/{$this->fight->fight_id}/{$this->fight->start_time}/{$this->fight->getEndTime()}/source/0/0/0/0/0/{$this->data->ability}/-1.0.-1/{$this->data->cutoff}/Any/Any/2";
        echo $url;
        $json = file_get_contents($url);
        $data = json_decode($json);

        if ($data === null) {
            return;
        }

        if (empty($data->events)) {
            return;
        }

        $firstEvent = reset($data->events);
        $source = $firstEvent->sourceID;

        $metricData = new BossMetricData();
        $metricData->fight_id = $this->fight->id;
        $metricData->metric_id = $this->metric_id;
        $metricData->unit_id = $source;
        $metricData->value = 1;
        $metricData->save();
    }
}