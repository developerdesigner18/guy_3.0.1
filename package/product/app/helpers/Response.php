<?php

namespace Altum;

class Response {

    public static function json($message, $status = 'success', $details = []) {
//        header('Content-type: application/json');
//        http_response_code($status == 'success' ? 200 : 400);

        if(!is_array($message) && $message) $message = [$message];

        echo json_encode(
            [
                'message' 	=> $message,
                'status' 	=> $status,
                'details'	=> $details,
            ]
        );


        die();
    }

    public static function simple_json($response) {

        echo json_encode($response);

        die();

    }

}
