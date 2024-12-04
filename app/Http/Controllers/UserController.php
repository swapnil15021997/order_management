<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    //

    public function user_add_edit(Request $request)
    {    
        $params = $request->all();
             

        $rules = [   
            'user_id'           => ['nullable','string'],
            'user_name'         => ['required','string'],  
            'user_address'      => ['required','string','max:255'],
            'user_phone_number' => ['required','string','max:13'],
            'user_role'         => ['required','string']
            ]; 
        $messages = [
            'user_name.required'         => 'User name is required.',
            'user_name.string'           => 'User name must be a string.',
            'user_address.required'      => 'Address is required.',
            'user_address.string'        => 'Address must be a string.',
            'user_address.max'           => 'Address cannot exceed 255 characters.',
            'user_phone_number.required' => 'Phone number is required.',
            'user_phone_number.string'   => 'Phone number must be a string.',
            'user_phone_number.max'      => 'Phone number cannot exceed 13 characters.',
            'user_role.required'         => 'User role is required.',
            'user_role.string'           => 'User role must be a string.',
        ]; 
        $validator = Validator::make($params, $rules, $messages);
        
        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0], 
                'errors'  => $validator->errors(), 
            ]);
        } 
        $check_role = UserRole::get_role_by_id($params['user_role']);
        if (empty($check_role)){
            return response()->json([
                'status' => 500,
                'message' => 'User role does not exist'
            ]);
        }

        if (empty($params['user_id'])){
            $get_data = User::get_data_by_phone_no($params['user_phone_number']);
            if (!empty($get_data)){
                return response()->json([
                    'status' => 500,
                    'message' => 'User does not exist'
                ]); 
            }
            $user = new User();
            $user->user_name         = $params['user_name'];
            $user->user_address      = $params['user_address'];
            $user->user_phone_number = $params['user_phone_number'];
            $user->user_role_id      = $params['user_role'];
            $user->user_hash_pass    = Hash::Make('Test@123'); 
            $user->user_password     = 'Test@123'; 
            $user->user_sweetword    = 'Test@123'; 
            
            
            $user->save();
        
            return response()->json([
                'status' => 200,
                'message' => 'User added successfully!',
                'user' => $user,
            ]);
        }else{
            $user = User::get_user_by_id($params['user_id']);
            if (!$user) {
                return response()->json([
                    'status' => 500,
                    'message' => 'User not found.',
                ]);
            }
            $user->user_name = $params['user_name'];
            $user->user_address = $params['user_address'];
            $user->user_phone_number = $params['user_phone_number'];
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => 'User updated successfully!'
            ]);
        }
    }

    public function user_remove(Request $request)
    {    

        $params = $request->all();
             
        $rules = [   
            'user_id'           => ['required','string']
            ]; 
        $messages = [
            'user_id.required'         => 'User id is required.',
            
        ]; 
        $validator = Validator::make($params, $rules, $messages);
        
        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0], 
                'errors'  => $validator->errors(), 
            ]);
        } 
        $user = User::get_user_by_id($params['user_id']);
        if (!$user) {
            return response()->json([
                'status' => 500,
                'message' => 'User not found.',
            ]);
        }
        $user->is_delete = 1;
        $user->save();
        return response()->json([
            'status' => 200,
            'message' => 'User removed successfully!'
        ]);
    }   

    public function user_list(Request $request)
    {    

        $rules = [
            'search'   => ['nullable', 'string'], 
            'per_page' => ['nullable', 'integer', 'min:1'], 
            'page'     => ['nullable', 'integer', 'min:1'], 
        ];
    
        $messages = [
            'search.string'   => 'Search query must be a valid string.',
            'per_page.integer' => 'Items per page must be a valid integer.',
            'per_page.min'     => 'Items per page must be at least 1.',
            'page.integer'     => 'Page number must be a valid integer.',
            'page.min'         => 'Page number must be at least 1.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0],
                'errors'  => $validator->errors(),
            ]);
        }
    
        $searchQuery = $request->input('search', ''); 
        $perPage = $request->input('per_page', 15);   
        $page = $request->input('page', 1);    
        $usersQuery = User::query();       
        if (!empty($searchQuery)) {
            $usersQuery->where(function ($query) use ($searchQuery) {
                $query->where('user_name', 'like', "%{$searchQuery}%")
                      ->orWhere('user_phone_number', 'like', "%{$searchQuery}%")
                      ->orWhere('user_role', 'like', "%{$searchQuery}%");
            });
        }

        $users = $usersQuery->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'status' => 200,
            'message' => 'User list fetched successfully!',
            'data' => $users, 
        ]);
    

    }


    public function user_details(Request $request){
        
        $params = $request->all();
             
        $rules = [   
            'user_id'           => ['required','string']
            ]; 
        $messages = [
            'user_id.required'         => 'User id is required.',
            
        ]; 
        $validator = Validator::make($params, $rules, $messages);
        
        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0], 
                'errors'  => $validator->errors(), 
            ]);
        } 
        $user = User::get_user_by_id($params['user_id']);
        if (!$user) {
            return response()->json([
                'status' => 500,
                'message' => 'User not found.',
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'User details fetch successfully.',
            'data' => $user
   
        ]);
    }



    // Roles and permissions

    
}
