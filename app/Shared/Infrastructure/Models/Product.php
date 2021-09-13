<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['name', 'price'];

    public $timestamps = false;

//    private ?int $id;
//
//    private ?string $name;
//
//    private ?int $price;
//
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    public function getName(): ?string
//    {
//        return $this->name;
//    }
//
//    public function setName($name): self
//    {
//        $this->name = $name;
//        return $this;
//    }
//
//    public function getPrice(): ?int
//    {
//        return $this->price;
//    }
//
//    public function setPrice($price): self
//    {
//        $this->price = $price;
//        return $this;
//    }
}
