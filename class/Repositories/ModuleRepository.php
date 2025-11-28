<?php

class ModuleRepository
{
    private $db;

    const TABLE_NAME = 'stu_ships_modules';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function total()
    {
        return $this->db->query("SELECT COUNT(*) FROM ".self::TABLE_NAME, [], 'value');
    }

    public function index($offset = 0, $limit = 50)
    {
        return $this->db->query("SELECT * FROM ".self::TABLE_NAME." LIMIT ? OFFSET ?", [$limit, $offset]);
    }
}