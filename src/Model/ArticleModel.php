<?php

namespace App\Model;

class ArticleModel extends MainModel
{

    private string $title;
    
    private string $content;

    private string $imgUrl;

    private string $imgAlt;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;

}
