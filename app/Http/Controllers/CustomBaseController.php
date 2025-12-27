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

            if ($result['status']) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            $statusCode = $result['statusCode'] ?? 200;
            unset($result['statusCode']);
            return response()->json($result, $statusCode);
        } catch (Exception $e) {
            DB::rollBack();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => $e->getMessage(),
                ], 500);
            } else {
                throw $e;
            }
        }
    }

    protected function handleException(callable $callback)
    {

        try {
            $result = $callback();

            $statusCode = $result['statusCode'] ?? 200;
            unset($result['statusCode']);
            return response()->json($result, $statusCode);
        } catch (Exception $e) {

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => $e->getMessage(),
                ], 500);
            } else {
                throw $e;
            }
        }
    }

    protected function validateData($rules, $messages = [], callable|null $beforeReturnErrorFunction = null)
    {
        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!empty($beforeReturnErrorFunction)) {
            $beforeReturnErrorFunction($validator);
        }
        if ($validator->fails()) {
            $errorData = sendError("Invalid input", $validator->errors(), false, 422);
            unset($errorData['statusCode']);
            $data = response()->json($errorData,422);
            abort($data);
        }
    }
}
