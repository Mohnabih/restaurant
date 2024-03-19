<?php

namespace App\Models\Menu;

use App\Traits\AvailabilityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, AvailabilityTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'description',
        'status',
        'type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'status' => 'boolean',
        'type' => 'integer'
    ];

    /**
     * Scope the query to include only parent categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope the query to include only parent categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Get the items for the category.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the subcategories for the category.
     */
    public function subCategories()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
