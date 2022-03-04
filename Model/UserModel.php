<?php

class UserModel extends Database
{
    public function getUsers($limit)
    {
        return $this->select("SELECT id, username, firstname, lastname FROM user ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }

    public function getUserByID($id)
    {
        return $this->select("SELECT id, username, firstname, lastname, password FROM user WHERE id = ?", ["i", $id]);
    }

    public function deleteUser($id)
    {
        return $this->delete("DELETE FROM user WHERE id = ?", ["i", $id]);
    }
}
