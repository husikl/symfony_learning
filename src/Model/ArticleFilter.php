<?php


namespace App\Model;


class ArticleFilter
{
    /**
     * @var int |null
     */
    private $id;

    /**
     * @var \DateTimeImmutable | null
     */
    protected $date;

    /**
     *@var string |null
     */
    protected $authors;

    /**
     *@var string |null
     */
    protected $content;

    /**
     * @return int |null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param int|null $id
     * @return ArticleFilter
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable|null $date
     */
    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthors(): ?string
    {
        return $this->authors;
    }

    /**
     * @param string|null $authors
     */
    public function setAuthors(?string $authors): self
    {
        $this->authors = $authors;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }


}

