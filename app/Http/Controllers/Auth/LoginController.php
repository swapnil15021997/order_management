<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Arr;
use DB;

class LoginController extends Controller
{
    //



    public function login(Request $request)
        {    
            $params = $request->all();
             

            $rules = [   
                'phone_no'     => ['required','string','max:13'],  
                'password'     => ['required','string','max:255']
                ]; 
            $messages = [
                'phone_no.required'   => 'Phone number required',
                'phone_no.string'     => 'Enter valid phone number',
                'password.required'   => 'Password field is required', 
                'password.string'     => 'Enter valid Password'
            ]; 
            $validator = Validator::make($params, $rules, $messages);
            
            if($validator->fails()){
                return response()->json([
                    'status' => 500,
                    'message' => Arr::flatten($validator->errors()->toArray())[0], // First error message
                    'errors'  => $validator->errors(), // All error messages
                ], 422);
            } 
            $get_data = User::get_data_by_phone_no($params['phone_no']);
            if (empty($get_data)){
                return response()->json([
                    'status' => 500,
                    'message' => 'User does not exist'
                ]); 
            }
            
            if (Hash::check($params['password'],$get_data->user_hash_pass)) {
                $user_token=md5($get_data->user_id.'-'.time() );

                DB::table('user_sessions')->insert([
                    'session_user_id' => $get_data->user_id,
                    'session_token' => $user_token,
                    'session_fcm_token' => $params['fcm_token'] ?? null, 
                    'session_user_device' => $params['device'] ?? null,
                    'session_expiry_time_stamp' => time() + (60 * 60 * 24 * 7), 
                    'session_status' => 1, 
                    'session_is_delete' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Login successful',
                    'token' => $user_token,
                    'user' => $get_data->user_id,
                ]); 
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Please provide a valid password'
                ]);

            }

        }


    public function logout(Request $request)
        {    
            $params = $request->all();
            $token = $request->header('Authorization');

            if (!$token) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Token does not exist'
                ]);
            }
            $session = DB::table('user_sessions')
                ->where('session_token', $token)
                ->where('session_status', 1) // Active sessions only
                ->where('session_is_delete', false)
                ->first();

            if (!$session) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Session not found'
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'User logout successfully'
            ]);


        }

    public function profile(Request $request){
            $token = $request->header('Authorization');

            if (!$token) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Token does not exist'
                ]);
            }
            $session = DB::table('user_sessions')
                ->where('session_token', $token)
                ->where('session_status', 1) // Active sessions only
                ->where('session_is_delete', false)
                ->first();

            if (!$session) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Session not found'
                ]);
            }
            $user_id = $session->get('user_id');

            $user = User::get_user_by_id($user_id);
            if (!$user) {
                return response()->json([
                    'status' => 500,
                    'message' => 'User not found.',
                ]);
            } 
            
            return response()->json([
                'status' => 200,
                'message' => 'Profile fetch successfully.',
                'data' => $user
    
            ]);
    }

    public function change_password(Request $request){
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json([
                'status' => 500,
                'message' => 'Token does not exist'
            ]);
        }
        $params = $request->all();
             
        $rules = [   
            'password'          => ['required','string','max:255'],  
            'new_password'          => ['required','string','max:255'],  
            'confirm_password'  => ['required','string','max:255']
            ]; 
        $messages = [
            'password.required'           => 'User password required',
            'password.string'             => 'Enter valid password',
            'confirm_password.required'   => 'Confirm User password required', 
            'confirm_password.string'     => 'Enter valid Confirm Password',
            'new_password.required'           => 'User password required',
            'new_password.string'             => 'Enter valid password',
            
        ]; 
        $validator = Validator::make($params, $rules, $messages);
        
        if($validator->fails()){
            return response()->json([
                'status' => 500,
                'message' => Arr::flatten($validator->errors()->toArray())[0], // First error message
                'errors'  => $validator->errors(), // All error messages
            ], 422);
        } 
     

        $session = DB::table('user_sessions')
            ->where('session_token', $token)
            ->where('session_status', 1) // Active sessions only
            ->where('session_is_delete', false)
            ->first();

        if (!$session) {
            return response()->json([
                'status' => 500,
                'message' => 'Session not found'
            ]);
        }
        $user_id = $session->session_user_id;

        $user = User::get_user_by_id($user_id);
        if (!$user) {
            return response()->json([
                'status' => 500,
                'message' => 'User not found.',
            ]);
        } 
 
        if (!Hash::check($params['password'], $user->user_hash_pass)) {
            return response()->json([
                'status' => 500,
                'message' => 'Please provide valid password!',
            ]);
        }
        if ($params['new_password'] !== $params['confirm_password']) {
            return response()->json([
                'status' => 500,
                'message' => 'Passwords do not match!',
            ]);
        }

        
        $sweetword = $user->user_sweetword ? json_decode($user->user_sweetword, true) : [];

        $sweetword[] = $params['new_password'];

        $sweetword = array_unique($sweetword);
        
        $user->user_hash_pass = Hash::make($params['new_password']);
        $user->user_sweetword = $sweetword;
        $user->save();
        return response()->json([
            'status' => 200,
            'message' => 'Update password successfully'
        ]);

    }

}
