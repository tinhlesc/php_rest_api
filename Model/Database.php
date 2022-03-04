<?php

class Database
{
    protected $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);

            if (mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");
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

    /**
     * @param $query
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function insert($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }


            $params['password'] = sha1($params['password']);

            $types = '';
            foreach ($params as $key => $param) {
                if (is_string($param)) {
                    $types .= 's';
                } elseif (is_int($param)) {
                    $types .= 'i';
                } elseif (is_double($param)) {
                    $types .= 'd';
                }

                $paramsTemp[] = $param;
            }

            $stmt->bind_param($types, ...$paramsTemp);

            $stmt->execute();
            return $stmt->insert_id;
        } catch (Exception $e) {
            $this->log($e->getMessage());
            throw new Exception('Something went wrong! Please contact support.');
        }

        return false;
    }

    protected function executeStatement($query = "", $params = [])
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

    public function update($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }
            if ($params) {
                $stmt->bind_param($params[0], $params[1]['username'], $params[1]['firstname'], $params[1]['lastname'], $params[1]['password']);
            }
            $stmt->execute();

            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
