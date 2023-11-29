<?php

namespace App\Model\Entity;

class ArticleEntity 
{

    private int $id;

    private string $title;
    
    private string $content;

    private string $imgUrl;

    private string $imgAlt;

    private DateTime $createdAt;

    private DateTime $updatedAt;


    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Set the value of id
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * Retrieves the title of the object.
     *
     * @return string The title of the object.
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * Set the title of the object.
     *
     * @param string $title The new title to set.
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


    /**
     * Retrieves the URL of the image.
     *
     * @return string The URL of the image.
     */
    public function getImageUrl(): string
    {
        return $this->imgUrl;
    }


    /**
     * Set the image URL.
     *
     * @param string $imgUrl The URL of the image to set.
     * @return void
     */
    public function setImageUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }


    /**
     * Retrieves the image alt attribute.
     *
     * @return string The image alt attribute.
     */
    public function getImageAlt(): string
    {
        return $this->imgAlt;
    }


    /**
     * Sets the alt text for an image.
     *
     * @param string $imgAlt The alt text to be set for the image.
     * @throws Some_Exception_Class A description of the exception that may be thrown.
     * @return void
     */
    public function setImageAlt(string $imgAlt): void
    {
        $this->imgAlt = $imgAlt;
    }


    /**
     * Returns the creation date of the object.
     *
     * @return \DateTime The creation date of the object.
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    /**
     * Sets the created date and time of the object.
     *
     * @param \DateTime $createdAt The created date and time.
     * @throws \Some_Exception_Class Description of exception.
     * @return void
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * Returns the updated date and time of the object.
     *
     * @return \DateTime The updated date and time.
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }


    /**
     * Set the updated at date and time.
     *
     * @param \DateTime $updatedAt The updated at date and time.
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}
