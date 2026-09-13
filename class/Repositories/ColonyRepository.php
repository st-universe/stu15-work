<?php

include_once ('BaseRepository.php');

class ColonyRepository extends BaseRepository
{
    protected $table = 'stu_colonies';

    public function index($offset = 0, $limit = 10)
    {
        return $this->db->query("SELECT id, colonies_classes_id, coords_x, coords_y, temp, weather, gravi, dn_mode, dn_duration, dn_nextchange, mkolz, wese FROM ".$this->table." LIMIT ? OFFSET ?", [$limit, $offset]);
    }
}
