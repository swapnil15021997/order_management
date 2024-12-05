<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Item;
use DB;
class Order extends Model
{
    use HasFactory;
 

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_date',
        'order_number',
        'order_qr_code',
        'order_from_branch_id',
        'order_to_branch_id',
        'order_user_id',
        'order_type'
    ];


    public static function get_order_by_qr_number_id($qr_number){
        $order = Order::where('order_qr_number',$qr_number)->where('is_delete',1)->first();
        return $order;
    }

    public static function get_order_by_number_id($order_number){
        $order = Order::where('order_number',$order_number)->where('is_delete',1)->first();
        return $order;
    }

    public static function get_order_by_id($order_id){
        $order = Order::where('order_id',$order_id)->where('is_delete',1)->first();
        return $order;
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'item_order_id', 'order_id');
    }

    // public static function get_order_with_items($order_id)
    // {
    //     $order = Order::where('order_id', $order_id)
    //                 ->where('is_delete', 0)
    //                 ->with('items') 
    //                 ->first();

    //     return $order;
    // }
    
    public static function get_order_with_items($order_id)
{
    $order = Order::where('order_id', $order_id)
                  ->where('is_delete', 0)
                  ->with('items')
                  ->first();
    if ($order) {
        foreach ($order->items as $item) {
            $item->files = File::whereIn('file_id', explode(',', $item->item_file_images))->get();
        }
    }
    return $order;
}


}
