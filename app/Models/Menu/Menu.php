<?php

namespace App\Models\Menu;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Get the user that owns the menu.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the item's discount.
     */
    public function discount()
    {
        return $this->morphOne(Discount::class, 'discountable');
    }
}
