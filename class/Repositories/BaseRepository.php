<?php

abstract class BaseRepository
{
    protected $db;
    protected $table;

    public function __construct($db)
    {
        $this->db = $db;
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