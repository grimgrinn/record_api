<?php

class Session
{
    private $conn;
    private $table = 'session';

    public $id;
    public $participantId;
    public $newsTitle;
    public $newsMessage;
    public $likesCounter;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'select * from ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readSingle()
    {
        $query = 'select * from ' . $this->table . 'where ID = ? limit 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam($this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['NewsTitle'];
        $this->body = $row['NewsMessage'];
        $this->likes = $row['LikesCounter'];

        return $stmt;
    }
}