<?php

include_once ('BaseRepository.php');

class UserRepository extends BaseRepository
{
    protected $table = 'stu_user';

    public function index($offset = 0, $limit = 10)
    {
        return $this->db->query("SELECT id, user, rasse, picture, halfnpc FROM ".$this->table." WHERE id < 100 LIMIT ? OFFSET ?", [$limit, $offset]);
    }
}