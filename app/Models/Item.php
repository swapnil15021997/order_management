<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
 

    protected $table = 'items';
    protected $primaryKey = 'item_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_metal',
        'item_name',
        'item_melting',
        'item_weight',
        'item_file_images',
        'item_order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'item_order_id', 'order_id');
    }
   

    public static function get_item_by_id($order_id){
        $order = Item::where('item_order_id', $order_id)
        ->where('is_delete', 0)
        ->first();
        return $order;
    }
}
