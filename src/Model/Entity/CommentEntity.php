<?php

namespace App\Model\Entity;

class CommentEntity extends MainModel
{

    private int $id;

    private int $authorId;

    private int $articleId;

    private string $content;

    private bool $approuved;

    private DateTime $createdAt;

    private DateTime $updatedAt;



    /**
     * Retrieves the ID of the object.
     *
     * @return int The ID of the object.
     */
    public function getId(): int {
        return $this->id;
    }
    

    /**
     * Sets the ID of the object.
     *
     * @param int $id The ID to set.
     *
     * @return void
     */
    public function setId(int $id): void {
        $this->id = $id;
    }


    /**
     * Retrieves the author ID.
     *
     * @return int The author ID.
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }


    /**
     * Sets the author ID.
     *
     * @param int $authorId The ID of the author.
     * @throws Some_Exception_Class The exception that can be thrown.
     * @return void
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }


    /**
     * Retrieves the article ID.
     *
     * @return int The article ID.
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }


    /**
     * Sets the article ID.
     *
     * @param int $articleId The ID of the article.
     * @throws Some_Exception_Class Description of exception
     * @return void
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }


    /**
     * Retrieves the content of the object.
     *
     * @return string The content of the object.
     */
    public function getContent(): string
    {
        return $this->content;
    }


    /**
     * Sets the content of the object.
     *
     * @param string $content The content to set.
     * @throws \Exception
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


    /**
     * Retrieves the value of the "approuved" property.
     *
     * @return bool The value of the "approuved" property.
     */
    public function getApprouved(): bool
    {
        return $this->approuved;
    }


    /**
     * Sets the value of the "approuved" property.
     *
     * @param bool $approuved The new value for the "approuved" property.
     * @throws Some_Exception_Class Description of exception
     * @return bool
     */
    public function setApprouved(bool $approuved): bool
    {
        $this->approuved = $approuved;
    }


    /**
     * Retrieves the creation date of the object.
     *
     * @return DateTime The creation date of the object.
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }


    /**
     * Sets the created at date and time.
     *
     * @param DateTime $createdAt The date and time to set as the created at value.
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * Retrieves the updated date and time of the object.
     *
     * @return DateTime The updated date and time.
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Sets the value of the $updatedAt property.
     *
     * @param DateTime $updatedAt The new value for the $updatedAt property.
     * @return void
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


}