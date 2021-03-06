<?php
/**
 * PatientZero.php
 * Creator: lehadnk
 * Date: 13/09/2017
 */

namespace App;


use App\Helpers\DB;

class PatientZero extends AbstractStrategy
{
    public function run() {
        $url = "https://www.warcraftlogs.com/reports/events/damage-taken/{$this->fight->log_id}/{$this->fight->fight_id}/{$this->fight->start_time}/{$this->fight->getEndTime()}/source/0/0/0/0/0/{$this->data->ability}/-1.0.-1/{$this->data->cutoff}/Any/Any/2";
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

    public function getReport($raidDate) {
        $result = \DB::select("
                SELECT fi.name, sum(bm.value) AS cnt
                FROM boss_metric_datas bm
                JOIN fight_units fi ON fi.unit_id = bm.unit_id AND bm.fight_id = fi.fight_id
                JOIN fights f ON bm.fight_id = f.id
                WHERE bm.metric_id = :id AND f.raid_date = :raidDate
                GROUP BY fi.name
                ORDER BY cnt DESC, name
        ", [
            ':raidDate' => $raidDate,
            ':id' => $this->metric_id,
        ]);

        $message = $this->metric->info_message.PHP_EOL.PHP_EOL;

        foreach ($result as $row) {
            $message .= "{$row->name}: {$row->cnt}".PHP_EOL;
        }

        return $message;
    }

    public function getPlayerDetailedReport($raidDate, $playerName) {
        $result = \DB::select("
                SELECT f.log_id, f.fight_id
                FROM boss_metric_datas bm
                JOIN fight_units fi ON fi.unit_id = bm.unit_id AND bm.fight_id = fi.fight_id
                JOIN fights f ON bm.fight_id = f.id
                WHERE bm.metric_id = :id AND f.raid_date = :raidDate AND fi.name = :playerName
        ", [
            ':raidDate' => $raidDate,
            ':id' => $this->metric_id,
            ':playerName' => $playerName
        ]);

        $message = "Изи! Детализация по $playerName:".PHP_EOL.PHP_EOL;

        foreach ($result as $row) {
            $message .= "https://www.warcraftlogs.com/reports/{$row->log_id}#view=events&fight={$row->fight_id}&type=deaths&ability={$this->data->ability}".PHP_EOL;
        }

        return $message;
    }
}