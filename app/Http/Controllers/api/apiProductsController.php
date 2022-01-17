<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Libraries\Helper;

class apiProductsController extends Controller
{
    public function order(Request $request)
    {
    	$rules = [
            'product_id' => 'required',
            'quantity' => 'required',
        ];


        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            $msg = $validator->messages();
            $errors = Helper::validateErrors($request,$msg);
            return response()->json(array('message' => $errors),400);
        }else{

	    	$product_data = DB::table('products')
			    			->select("*")
			    			->where("id",$request->product_id)
			    			->first();

			if($product_data->available_stock >= $request->quantity)
			{
				$remaining = $product_data->available_stock - $request->quantity;

				// dd($remaining);

				$update_stocks = DB::table('products')
			    					->where("id",$request->product_id)
			    					// ->get();
			    					->update(["available_stock" => $remaining]);

			    return response()->json(array('message' => "You have successfully ordered this product."),201);

			}else{
				return response()->json(array('message' => "Failed to order this product due to unavailability of the stock"),400);
			}

		} 

    }
}
