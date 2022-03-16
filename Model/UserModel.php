<?php

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

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
    public function createUser($userData)
    {
        return$this->insert("INSERT INTO user (username, lastname, firstname, password) VALUES (?,?,?,?)", $userData);
    }

    public function getUserByUsername($username)
    {
        return $this->select("SELECT id, username, firstname, lastname, password FROM user WHERE username = ?", ["s", $username]);
    }

    public function updateUser($id, $updateData = [])
    {
        return $this->update("UPDATE USER SET username = ?, firstname = ?, lastname = ?, password = ? WHERE id = " . $id, ["ssss", $updateData]);
    }
}
