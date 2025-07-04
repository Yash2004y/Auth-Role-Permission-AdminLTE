<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomBaseController extends Controller
{
    protected function handleTransaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();

            if(request()->wantsJson() || request()->ajax()){
                return response()->json([
                    'status' => true,
                    'message' => $e->getMessage(),
                ], 500);
            }
            else{
                throw $e;
            }
        }
    }

    protected function validateData($rules,$messages=[],callable|null $beforeReturnErrorFunction = null){
        $validator = Validator::make(request()->all(),$rules,$messages);
        if(!empty($beforeReturnErrorFunction)){
            $beforeReturnErrorFunction($validator);
        }
        if($validator->fails()){
            abort(sendError("Invalid input",$validator->errors(),false,422));
        }
    }

}
