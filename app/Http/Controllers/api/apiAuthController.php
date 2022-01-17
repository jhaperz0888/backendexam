<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Libraries\Helper;

class apiAuthController extends Controller
{
   public function register(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|string|email|unique:users',
        //     'password' => 'required|string',
        // ]);

    //     $user = new User([
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //     ]);

    //     $user->save();

        $rules = [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            $msg = $validator->messages();
            $errors = Helper::validateErrors($request,$msg);
            return response()->json(array('message' => $errors),400);
        }else{

        	$user = array(
                            'name' => null,
                            'email' => $request->email,
                            'password' => bcrypt($request->password),
                            'created_at' => Helper::getDateTimePHP(),
                            'updated_at' => Helper::getDateTimePHP()
                        );

        	$user_id = DB::table("users")->insertGetId($user);

	        return response()->json([
	            'message' => 'Successfully registered'
	        ], 201);

	    }
    }
}
