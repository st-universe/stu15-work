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
        return $this->db->query(
            "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_KEY, EXTRA
                FROM information_schema.columns
                WHERE table_schema = DATABASE()
                    AND table_name = ?
                ORDER BY ORDINAL_POSITION",
            [$this->table]
        );
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
