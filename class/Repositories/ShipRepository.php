<?php

include_once ('BaseRepository.php');

class ShipRepository extends BaseRepository
{
    protected $table = 'stu_ships';

    public function index($offset = 0, $limit = 10)
    {
        return $this->db->query("SELECT id, ships_rumps_id, epsupgrade, huellmodlvl, sensormodlvl, waffenmodlvl, schildmodlvl, reaktormodlvl, antriebmodlvl, computermodlvl, epsmodlvl FROM ".$this->table." LIMIT ? OFFSET ?", [10, 0]);
    }
}