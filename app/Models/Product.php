<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $price
 */
class Product extends Model
{
    protected $table = 'products';

    /** @var list<string> */
    protected $fillable = ['name', 'price'];

    public $timestamps = false;
}
