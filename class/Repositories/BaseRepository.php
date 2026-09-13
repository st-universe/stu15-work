<?php

abstract class BaseRepository
{
    protected $db;
    protected $table;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function columns()
    {
        return $this->db->query("SELECT COLUMN_NAME FROM information_schema.columns WHERE table_name = ".$this->table, [], 'value');
    }

    public function total()
    {
        return $this->db->query("SELECT COUNT(*) FROM ".$this->table, [], 'value');
    }

    public function index($offset = 0, $limit = 50)
    {
        return $this->db->query("SELECT * FROM ".$this->table." LIMIT ? OFFSET ?", [$limit, $offset]);
    }
}