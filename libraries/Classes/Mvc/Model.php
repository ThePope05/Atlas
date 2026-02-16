<?php

namespace Libraries\Classes\Mvc;

use Libraries\Classes\Database\QueryBuilder;
use PDO;
use PDOException;

abstract class Model
{
    protected QueryBuilder $db;

    public function __construct()
    {
        $conn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=UTF8';

        try {
            $pdo = new PDO($conn, DB_USER, DB_PASS);

            if ($pdo) {
                // echo "Connected to the database";
            } else {
                echo "Internal server error";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $this->db = new QueryBuilder($pdo);
    }
}
