<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Model\Category;
use App\Model\Seller;
use App\Model\Product;
class PopularCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

public function seller()
{
    return $this->belongsTo(Seller::class);
}

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')
            ->where('seller_id', $this->seller_id);
    }
}
