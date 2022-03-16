<?php

class Database
{
    protected $connection = null;
    protected $logManage = null;

    public function __construct()
    {
        try {
            $this->logManage = new LogManagement();
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

            if (mysqli_connect_errno()) {
                $this->logManage->addLog("(Model)Database", "Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function select($query = "", $params = [])
    {
        $arrayValue = [];

        try {
            $stmt = $this->executeStatement($query, $params);
            if ($stmt) {
                $arrayValue = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
            $stmt->close();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $arrayValue;
    }

    protected function delete($query = "", $params = [])
    {
        $isDeleted = false;

        try {
            $stmt = $this->executeStatement($query, $params);
            if ($stmt) {
                $isDeleted = true;
            }
            $stmt->close();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $isDeleted;
    }

    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if ($params) {
                $stmt->bind_param($params[0], $params[1]);
            }
            $stmt->execute();

            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
