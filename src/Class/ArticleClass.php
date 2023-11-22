<?php

namespace App\Class;

class Article
{
    private $id;
    private $title;
    private $content;
    private $imgUrl;
    private $imgAlt;
    private $createdAt;
    private $updatedAt;

    public function __construct($title, $content, $imgUrl, $imgAlt)
    {
        $this->title = $title;
        $this->content = $content;
        $this->imgUrl = $imgUrl;
        $this->imgAlt = $imgAlt;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Getters and Setters for all the properties

    // ...
}