<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function rating(): float
    {
        $this->load('reviews');

        return round($this->reviews->average('stars'), 1);
    }

    public function name(): Attribute
    {
        return Attribute::set(fn ($value) => Str::title($value));
    }

    public function showRoute(array $parameters = [])
    {
        return route('products.show', [$this, Str::slug($this->name), ...$parameters]);
    }
}
