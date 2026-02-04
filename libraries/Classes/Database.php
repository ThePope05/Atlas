<?php

namespace Libraries\Classes;

use Exception;
use PDO;
use PDOException;

class Database
{
    private $dbHandler;
    private $statement;

    public function __construct()
    {
        $conn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=UTF8';

        try {
            $this->dbHandler = new PDO($conn, DB_USER, DB_PASS);

            if ($this->dbHandler) {
                // echo "Connected to the database";
            } else {
                echo "Internal server error";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function query($sql)
    {
        $this->statement = $this->dbHandler->prepare($sql);
    }

    public function bind($parameter, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        //var_dump($parameter, $value);
        $this->statement->bindValue($parameter, $value, $type);
    }

    public function execute(bool $return = false)
    {
        if ($return) {
            try {
                $this->statement->execute();
            } catch (Exception $e) {
                throw new Exception("Error Processing Request {$e}", 1);
            }
            return $this->statement->fetchAll(PDO::FETCH_OBJ);
        } else {
            return $this->statement->execute();
        }
    }

    public function DebugDump()
    {
        $this->statement->debugDumpParams();
    }

    public function fetch()
    {
        return $this->execute(true);
    }

    public function first()
    {
        return $this->execute(true)[0];
    }
}
