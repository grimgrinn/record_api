<?php

class Session
{
    private $conn;
    private $table = 'session';

    public $id;
    public $name;
    public $timeOfEvent;
    public $description;
    public $maxPeople;

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

        $this->name = $row['Name'];
        $this->timeOfEvent = $row['TimeOfEvent'];
        $this->description = $row['Description'];
        $this->maxPeople = $row['max_people'];

        return $stmt;
    }

    public function book($userEmail)
    {
        $query = 'select max_people from session where ID = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $this->maxPeople = (int) $stmt->fetch(PDO::FETCH_ASSOC)['max_people'];

        $query = 'select * from participant where Email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userEmail);
        $stmt->execute();
        $num = $stmt->rowCount();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($num === 0) {
            echo jsonAnswer([], 'error', 'Нет такого пользователя');
            return;
        }

        $query = 'select count(*) from bookings where session_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $booked = (int) $stmt->fetch(PDO::FETCH_NUM);
        if($booked >= $this->maxPeople) {
            return false;
        } else {
            $query = 'insert into bookings set session_id = :session_id, user_id = :user_id';
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':session_id', $this->id);
            $stmt->bindParam(':user_id', $user['ID']);

            if($stmt->execute()) {
                return true;
            }

            echo jsonAnswer([],'error',$stmt->error);
            return false;
        }

    }
}