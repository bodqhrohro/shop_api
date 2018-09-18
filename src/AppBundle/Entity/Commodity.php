<?php

namespace AppBundle\Entity;

use JsonSerializable;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table
 * @ORM\Entity
 */
class Commodity implements JsonSerializable {
   /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type="integer")
    */
    protected $id;

   /**
    * @ORM\Column(type="string")
    */
    protected $name;

   /**
    * @Assert\GreaterThan(0)
    * @ORM\Column(type="decimal", scale=2)
    */
    protected $price;

   /**
    * @Assert\DateTime()
    * @ORM\Column(type="datetime")
    */
    protected $created_at;

    public function __construct() {
        $this->created_at = new \DateTime();
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price) {
        $this->price = $price;
    }

    public function getCreatedAt(): \DateTime {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at) {
        $this->created_at = $created_at;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (float)$this->price,
            'created_at' => $this->created_at->format(\DateTime::RFC3339_EXTENDED),
        ];
    }
}
