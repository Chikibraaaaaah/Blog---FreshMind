<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="blog_comment")
 */
class CommentEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="authorId")
     * @ORM\ManyToOne(targetEntity="UserEntity", mappedBy="id")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $authorId;

    /**
     * @ORM\Column(type="integer", name="articleId")
     * @ORM\ManyToOne(targetEntity="ArticleEntity", inversedBy="id")
     * @ORM\JoinColumn(name="articleId", referencedColumnName="id")
     */
    private $articleId;

    /**
     * @ORM\Column(type="text", name="content")
     */
    private $content;

    /**
     * @ORM\Column(type="string", name="approved")
     */
    private $approved;

    /**
     * @ORM\Column(type="datetime", name="createdAt")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updatedAt")
     */
    private $updatedAt;

    // Getters et setters pour chaque propriété...

    /**
     * Retrieves the ID of the object.
     *
     * @return int|null The ID of the object, or null if it does not have an ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Retrieves the author ID.
     *
     * @return int|null The author ID.
     */
    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }


    /**
     * Sets the author ID.
     *
     * @param int $authorId The ID of the author.
     * @return void
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }


    /**
     * Retrieves the article ID.
     *
     * @return int|null The article ID, or null if not set.
     */
    public function getArticleId(): ?int
    {
        return $this->articleId;
    }


    /**
     * Sets the article ID.
     *
     * @param int $articleId The ID of the article.
     * @throws Some_Exception_Class Description of the exception.
     * @return void
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }


    /**
     * Get the content.
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
     * Retrieves the approved value.
     *
     * @return string|null The approved value.
     */
    public function getApproved(): ?string
    {
        return $this->approved;
    }


    /**
     * Sets the value of the "approved" property.
     *
     * @param string $approved The new value for the "approved" property.
     * @throws \Exception If the provided value is not of type string.
     * @return void
     */
    public function setApproved(string $approved): void
    {
        $this->approved = $approved;
    }


    /**
     * Retrieves the value of the `createdAt` property.
     *
     * @return \DateTimeInterface|null The value of the `createdAt` property.
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * Sets the value of the createdAt property.
     *
     * @param \DateTimeInterface $createdAt The new value for the createdAt property.
     * @return void
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * Retrieves the value of the 'updatedAt' property.
     *
     * @return \DateTimeInterface|null The value of the 'updatedAt' property.
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    /**
     * Set the updated at date and time.
     *
     * @param \DateTimeInterface $updatedAt The updated at date and time.
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}
