<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $with = ['discount'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['price_after_discount'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'price' => 'float',
        'status' => 'boolean'
    ];

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the item's discount.
     */
    public function discount()
    {
        return $this->morphOne(Discount::class, 'discountable');
    }

    /**
     * Get the item's discount.
     *
     * @param  string  $value
     * @return string
     */
    public function getPriceAfterDiscountAttribute($value)
    {
        if ($discount = $this->discount)
            return round($this->price - ($discount->amount / 100 * $this->price), 2);
        elseif ($discount = $this->category->discount)
            return round($this->price - ($discount->amount / 100 * $this->price), 2);
        elseif ($discount = Auth::user()->menu->discount)
            return round($this->price - ($discount->amount / 100 * $this->price), 2);
        else return null;
    }
}
