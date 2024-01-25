<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\Setup;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_article")
 */
class ArticleEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;


    /** @ORM\Column(type="integer", name="authorId") 
     * @ORM\ManyToOne(targetEntity="App\Entity\UserEntity")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
    */
    private int $authorId;

    /**
     * @ORM\Column(type="string", name="title")
     */
    private $title;


    /** @ORM\Column(type="string", name="chapô") */
    private string $chapô;

    /**
     * @ORM\Column(type="text", name="content")
     */
    private $content;

    /**
     * @ORM\Column(type="string", name="imgUrl")
     */
    private $imgUrl;

    /**
     * @ORM\Column(type="string", name="imgAlt")
     */
    private $imgAlt;

    /**
     * @ORM\Column(type="datetime", name="createdAt")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updatedAt")
     */
    private $updatedAt;

    /** Getters et Setters */

    /**
     * Retrieves the ID of the object.
     *
     * @return int|null The ID of the object.
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Retrieves the title of the object.
     *
     * @return string|null The title of the object, or null if it has no title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * Sets the title of the object.
     *
     * @param string $title The new title to set.
     * @throws Exception If the title is not a string.
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    /**
     * Retrieves the content.
     *
     * @return string|null The content.
     */
    public function getContent(): ?string
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
     * Retrieves the image URL.
     *
     * @return string|null The image URL.
     */
    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }


    /**
     * Sets the image URL for the object.
     *
     * @param string $imgUrl The URL of the image.
     * @throws \Exception If the image URL is invalid.
     * @return void
     */
    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }


    /**
     * Retrieves the value of the imgAlt property.
     *
     * @return string|null The value of the imgAlt property.
     */
    public function getImgAlt(): ?string
    {
        return $this->imgAlt;
    }


    /**
     * Sets the alt text for an image.
     *
     * @param string $imgAlt The alt text to set.
     * @throws \Exception If an error occurs.
     * @return void
     */
    public function setImgAlt(string $imgAlt): void
    {
        $this->imgAlt = $imgAlt;
    }


    /**
     * Retrieves the createdAt value of the object.
     *
     * @return \DateTimeInterface|null The createdAt value or null if not set.
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * Set the creation date and time of the object.
     *
     * @param \DateTimeInterface $createdAt The creation date and time.
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * Retrieves the value of the updatedAt property.
     *
     * @return \DateTimeInterface|null The updatedAt property value.
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    /**
     * Sets the "updatedAt" property of the object.
     *
     * @param \DateTimeInterface $updatedAt The updated date and time.
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}
