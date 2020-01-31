<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use midorikocak\arraytools\ArrayConvertableTrait;

class Entry
{
    use ArrayConvertableTrait;

    private ?string $id;
    private string $content;
    private ?string $created;

    public function __construct(string $content, ?string $created = null, ?string $id = null)
    {
        $this->id = $id;
        $this->content = $content;
        $this->created = $created;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(string $created)
    {
        $this->created = $created;
    }
}
