<?php

namespace App\Http\Parsers\Entities;

final class ParserProduct
{

    /**
     * @var string|null
     */
    private ?string $ext_id = null;

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $link = null;

    /**
     * @var string|null
     */
    private ?string $desc = null;

    /**
     * @var string|null
     */
    private ?string $old_price = null;

    /**
     * @var string|null
     */
    private ?string $price = null;

    /**
     * @var string|null
     */
    private ?string $img = null;


    public function __construct(string $id)
    {
        $this->setExtId($id);
    }


    public function setExtId(?string $extId): self
    {
        $this->ext_id = $extId;

        return $this;
    }


    public function getExtId(): ?string
    {
        return $this->ext_id;
    }


    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }


    public function getLink(): ?string
    {
        return $this->link;
    }


    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }


    public function getImg(): ?string
    {
        return $this->img;
    }


    public function setDesc(?string $desc): self
    {
        $this->desc = trim($desc);

        return $this;
    }


    public function getDesc(): ?string
    {
        return $this->desc;
    }


    public function setOldPrice(?string $oldPrice): self
    {
        $this->old_price = $oldPrice;

        return $this;
    }


    public function getOldPrice(): ?string
    {
        return $this->old_price;
    }


    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getPrice(): ?string
    {
        return $this->price;
    }
}
