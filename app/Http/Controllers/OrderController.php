<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Item;
use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class OrderController extends Controller
{
    public function order_add(Request $request){
        $params = $request->all();

        $rules = [   
            
            'order_date'           => ['required', 'date', 'date_format:Y-m-d'],  
            'order_from_branch_id' => ['required','string'],
            'order_to_branch_id'   => ['required','string'],
            'order_type'           => ['required','in:1,2'],
            'item_metal'           => ['required', 'string'],
            'item_name'            => ['required', 'string'],
            'item_melting'         => ['required', 'string'],
            'item_weight'          => ['required', 'numeric'],
            'item_file_images'     => ['nullable'],  
            'item_file_images.*'   => ['file', 'mimes:jpeg,jpg,png,pdf', 'max:10240'],  
            ]; 
        $messages = [
                'order_date.required'         => 'Order date is required.',
                'order_date.date'             => 'Order date must be a valid date.',
                'order_date.date_format'      => 'Order date must be in the format YYYY-MM-DD.',            
                'order_from_branch_id.required' => 'From branch ID is required.',
                'order_from_branch_id.string' => 'From branch ID must be a string.',
                'order_to_branch_id.required' => 'To branch ID is required.',
                'order_to_branch_id.string'   => 'To branch ID must be a string.',
                'order_type.required'         => 'Order type is required.',
                'order_type.string'           => 'Order type must be a string.',
                'order_type.in'               => 'Order type must be 1 or 2.',
                
                'item_metal.required'         => 'Item metal is required.',
                'item_metal.string'           => 'Item metal must be a string.',
                'item_name.required'          => 'Item name is required.',
                'item_name.string'            => 'Item name must be a string.',
                'item_melting.required'       => 'Item melting is required.',
                'item_melting.string'         => 'Item melting must be a string.',
                'item_weight.required'        => 'Item weight is required.',
                'item_weight.numeric'         => 'Item weight must be a number.',
                'item_file_images.array'      => 'Item file images must be an array.',
                'item_file_images.*.file'     => 'Each item file image must be a valid file.',
                'item_file_images.*.mimes'    => 'Each item file image must be a jpeg, jpg, png, or pdf file.',
                'item_file_images.*.max'      => 'Each item file image cannot exceed 10MB.',

            ]; 

        $validator = Validator::make($params, $rules, $messages);
        
        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0], 
                'errors'  => $validator->errors(), 
            ]);
        } 

        $branch = Branch::get_branch_by_id($params['order_from_branch_id']);

        if (empty($branch)) {
            return response()->json([
                'status'  => 500,
                'message' => 'Branch does not exists' 
            ]);
        }
        $to_branch = Branch::get_branch_by_id($params['order_to_branch_id']);
        if (empty($to_branch)) {
            return response()->json([
                'status'  => 500,
                'message' => 'Branch does not exists' 
            ]);
        }     

        $order_number   = $this->generateUniqueNumber('order_number');
        $qr_code_number = $this->generateUniqueNumber('order_qr_code');
    

        $order                       = new Order();
        $order->order_date           = $params['order_date'];
        $order->order_number         = $order_number;
        $order->order_qr_code        = $qr_code_number;
        $order->order_from_branch_id = $params['order_from_branch_id'];
        $order->order_to_branch_id   = $params['order_to_branch_id'];
        $order->order_type           = $params['order_type'];
        $order->save();

        
        $item = new Item();
        $item->item_metal = $params['item_metal'];
        $item->item_name = $params['item_name'];
        $item->item_melting = $params['item_melting'];
        $item->item_weight   = $params['item_weight'];
        $item->item_order_id = $order->order_id;
        $item->save();

        $fileIds = [];
        if ($request->hasFile('item_file_images')) {
            $files = $request->file('item_file_images');
            \Log::info('Files uploaded: ' . count($files));
            foreach ($files as $file) {

                $filePath = $file->store('uploads', 'public'); 
                $fileModel = new File();
                $fileModel->file_name = $file->hashName(); 
                $fileModel->file_original_name = $file->getClientOriginalName();
                $fileModel->file_path = $filePath;
                $fileModel->file_url = asset('storage/' . $filePath); 
                $fileModel->file_type = $file->getClientMimeType();
                $fileModel->file_size = $file->getSize();
                $fileModel->save();
                $fileIds[] = $fileModel->file_id;
            }
        }
        $item->item_file_images = implode(',', $fileIds);
        $item->save();

        return response()->json([
            "status" =>200,
            "message"=>"Order created successfully"
        ]);
    }


    private function generateUniqueNumber($column)
    {
        do {
            $number = mt_rand(1000000000, 9999999999); // Generate a 10-digit number
        } while (Order::where($column, $number)->exists()); // Check for uniqueness

        return $number;
    }

    public function order_details(Request $request){
        $params = $request->all();
             

        $rules = [   
            
            'order_id' => ['required','string'],
           
            ]; 
        $messages = [
 
                'order_id.required'         => 'Order id is required.',
                'order_id.string'           => 'Order id must be a string.'

            ]; 
            
        $validator = Validator::make($params, $rules, $messages);
        
        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0], 
                'errors'  => $validator->errors(), 
            ]);
        } 

        $check_order = Order::get_order_with_items($params['order_id']);
        if (empty($check_order)){
            return response()->json([
                'status' => 500,
                'message' => 'Order does not exist'
            ]);
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Order details fetch successfully',
            'data'    => $check_order
        ]);

    }

    public function order_list(Request $request){
        
    }
    public function order_remove(Request $request){
        $params = $request->all();
             

        $rules = [   
            
            'order_id' => ['required','string'],
           
            ]; 
        $messages = [
 
                'order_id.required'         => 'Order id is required.',
                'order_id.string'           => 'Order id must be a string.'

            ]; 
            
        $validator = Validator::make($params, $rules, $messages);
        
        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0], 
                'errors'  => $validator->errors(), 
            ]);
        } 

        $check_order = Order::get_order_with_items($params['order_id']);
        if (empty($check_order)){
            return response()->json([
                'status' => 500,
                'message' => 'Order does not exist'
            ]);
        }

        $check_order->is_delete = true;
        $check_order->save();
        $item = Item::get_item_by_id($check_order->order_id);
        if (!empty($item)){
            $item->is_delete = 1;
            $item->save();
        }
        return response()->json([
            'status'  => 200,
            'message' => 'Order removed successfully'

        ]);
    }
}
