<?php

namespace App\Libraries;

class JsonResponse {

    public static function Success($data, $message = null) 
    {
        $res = [
            'status'    =>  200,
            'success'   =>  true,
            'message'   =>  $message,
            'data'      =>  $data
        ];

        return response()->json($res, 200);
    }

    public static function Failed($message)
    {
        $res = [
            'status'    =>  400,
            'success'   =>  false,
            'message'   =>  $message,
            'data'      =>  []
        ];

        return response()->json($res, 400);
    }

    public static function NotFound($message)
    {
        $res = [
            'status'    =>  404,
            'success'   =>  false,
            'message'   =>  $message,
            'data'      =>  []
        ];

        return response()->json($res, 404);
    }

    public static function BadRequest($message)
    {
        $res = [
            'status'    =>  422,
            'success'   =>  false,
            'message'   =>  $message,
            'data'      =>  []
        ];

        return response()->json($res, 422);
    }

    public static function BadDynamic($message, $status)
    {
        $res = [
            'status'    =>  $status,
            'success'   =>  false,
            'message'   =>  $message,
            'data'      =>  []
        ];

        return response()->json($res, $status);
    }
}