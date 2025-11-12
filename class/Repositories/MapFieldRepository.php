<?php

include_once('class/Database.php');

class MapFieldRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function total()
    {
        return $this->db->query("SELECT COUNT(*) FROM stu_map_fields", [], 'value');
    }

    public function index($offset = 0, $limit = 50)
    {
        return $this->db->query("SELECT * FROM stu_map_fields LIMIT ? OFFSET ?", [$limit, $offset]);
    }
}