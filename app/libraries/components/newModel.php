<?php

class MODEL_NAME extends BaseModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    //Example of a query
    public function index(int $id)
    {
        //Here you can write your query
        $this->db->query("SELECT * FROM TABLE_NAME WHERE id = :id");

        //Here you can bind your parameters
        $this->db->bind(':id', $id);

        //Here you can execute your query and return the result if you want
        return $this->db->execute(true);
    }
}
