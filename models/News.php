<?php

include_once 'ApiItemInterface.php';

class News implements ApiItemInterface
{
    private $conn;
    private $table = 'news';

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
        $query = 'select * from ' . $this->table . ' where ID = ? limit 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->newsTitle = $row['NewsTitle'];
            $this->newsMessage = $row['NewsMessage'];
            $this->likesCounter = $row['LikesCounter'];
            $this->participantId = $row['ParticipantId'];
        }
    }

    public function create()
    {
        $query = 'insert into ' . $this->table . '
        set
            NewsTitle = :title,
            NewsMessage = :message
            ';

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->newsTitle));
        $this->title = htmlspecialchars(strip_tags($this->newsMessage));

        $stmt->bindParam(':title', $this->newsTitle);
        $stmt->bindParam(':message', $this->newsMessage);

        if($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}