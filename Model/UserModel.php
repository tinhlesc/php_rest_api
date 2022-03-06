<?php

    require_once PROJECT_ROOT_PATH . "/Model/Database.php";

    class UserModel extends Database
    {
        public function getUsers($limit)
        {
            return $this->select("SELECT id, username, firstname, lastname FROM user ORDER BY id ASC LIMIT ?", ["i", $limit]);
        }
    }
