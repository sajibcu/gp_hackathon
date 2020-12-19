<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;
use CodeIgniter\HTTP\Response;
// Prints Array and stops further execution
if (!function_exists('dd')){
    function dd($array) {
        echo '<pre>'; var_export($array); echo '</pre>'; exit;
    }
}

## example: converts 2017-11-04 to 4/11/2017
if (!function_exists('nice_date')){
    function nice_date($date){
        return empty($date) || $date=='0000-00-00' ? '' : date("d/m/Y", strtotime($date));
    }
}

## converst mysql datetime format to viewable datetime format
## example: converts "2017-01-31 13:05:59" to "31/01/17 at 1:05 pm"
if (!function_exists('nice_datetime')){
    function nice_datetime($datetime){
        if(empty($datetime) || $datetime == '0000-00-00') return '';
        return date('d/m/Y g:i A', strtotime($datetime));
        // return strtolower(date('d/m/Y \a\t g:i A', strtotime($datetime)));
    }
}
 
// $autoload['helper'] =  array('function_helper');

if (!function_exists('isAuthonticated')){
    function isAuthonticated(){
        $ci = & get_instance();
        $headers = $ci->input->get_request_header('Authorization'); //get token from request header
        // dd($headers);
        $config = $ci->config->item('jwt_config');

        try {
           $decoded = JWT::decode($headers, $config['secretKey'], array('HS512'));
           $decoded_array = (array) $decoded;

           //dd($decoded_array);
           //$response = service('response');
          // $ci->response->statusCode(200);
           return true;
          // $ci->response->setStatusCode(Response::HTTP_OK);

          // $ci->set_response($decoded, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $invalid = ['status' => "Error",'message' => "You are not authorize!!"]; //Respon if credential invalid
            echo json_encode($invalid);
            die();
            return false;
             
        }
    }
}