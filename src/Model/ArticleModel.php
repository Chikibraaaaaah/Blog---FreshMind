<?php

namespace App\Model;

class ArticleModel extends MainModel
{

    private int $id;

    private string $title;

    private string $chapô;
    
    private string $content;

    private string $imgUrl;

    private string $imgAlt;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

}
