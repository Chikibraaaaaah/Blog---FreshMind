<?php

namespace App\Model;

use App\Model\Factory\ModelFactory;

class CommentModel extends MainModel
{
    private int $id;

    private int $authorId;

    private int $articleId;

    private string $content;

    private string $approuved;

    private DateTime $createdAt;

    private DateTime $updatedAt;
    

    public function readComment(string $value, string $key=null)
    {
        if (isset($key) === TRUE) {
            $query = "SELECT comment.*, user.userName
                      FROM " . $this->table . " AS comment
                      INNER JOIN user ON user.id = comment.authorId
                      WHERE " . $key . " = ?";
        } else {
            $query = "SELECT comment.*, user.userName
                      FROM " . $this->table . " AS comment
                      INNER JOIN user ON user.id = comment.authorId
                      WHERE id = ?";
        }
    
        return $this->database->getData($query, [$value]);
    }

        public function listComment(string $value=null, string $key=null)
    {
        if (isset($key) === TRUE) {
            
            $query = " SELECT comment.*, user.userName
                        FROM " . $this->table . " AS comment
                        INNER JOIN user ON user.id = comment.authorId
                        WHERE " . $key . " = ?";

            return $this->database->getAllData($query, [$value]);
        }

        $query = "  SELECT comment.*, user.userName
                    FROM " . $this->table . " AS comment
                    INNER JOIN user ON user.id = comment.authorId;";

        return $this->database->getAllData($query);
    }
}