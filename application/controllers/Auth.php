<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;
class Auth extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

     public function __construct()
    {
        parent::__construct();
        
        $this->load->model(array(
            'user_model',
        ));
    }

    public function login()
    {
        $this->form_validation->set_rules('email', "email",'required|valid_email|max_length[32]');
        $this->form_validation->set_rules('password', "password",'required|max_length[255]');

        $data = array(
            'status'=>'error',
            'message'=>'',
            'token'=>'',
        );

        if ($this->form_validation->run() === true) {

            $postData = array(
            'email'                 => $this->input->post('email',true),
            'password'              => sha1($this->input->post('password',true)),
            );

            $user = $this->user_model->checkUserIsValid($postData['email'],$postData['password']); 
            // dd($user);

            if(!empty($user)) {
                /*
             * Create the token as an array
             */
                $config = $this->config->item('jwt_config');
                $jwtData = [
                    'iat'  => $config['issuedAt'],         // Issued at: time when the token was generated
                    'jti'  => $config['tokenId'],          // Json Token Id: an unique identifier for the token
                    'iss'  => $config['serverName'],       // Issuer
                    'nbf'  => $config['notBefore'],        // Not before
                    'exp'  => $config['expire'],           // Expire
                    'data' => [                  // Data related to the signer user
                        'id'   => $user['id'], // userid from the users table
                        'email' => $user['email'], // User name
                    ]
                ];
                // dd($jwtData);

                $jwt = JWT::encode(
                $jwtData,      //Data to be encoded in the JWT
                 $config['secretKey'], // The signing key
                'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
                );
                // echo $jwt;
                //$token = JWT::decode($jwt, $secretKey, array('HS512'));
                //print_r($token);

                $data['status'] = "success";
                $data['message'] = "Please user token for authization";
                $data['token'] = $jwt;

            } else {
                $data['status'] = "error";
                $data['message'] = "Please Try again!!";
                $data['token'] = "";
            }

        }else {
            $data['status'] = "error";
            $data['message'] = "Please Try again!!";
            $data['token'] = "";
            
        }

        echo json_encode($data);
    }

    function isAuthonticated(){
       // $ci = & get_instance();
        $headers = $this->input->get_request_header('Authorization'); //get token from request header
        // dd($headers);
        $config = $this->config->item('jwt_config');

        try {
           $decoded = JWT::decode($headers, $config['secretKey'], array('HS512'));
           $decoded_array = (array) $decoded;

           //dd($decoded_array);
           //$response = service('response');
           $ci->response->statusCode(200);
           // return true;
          // $ci->response->setStatusCode(Response::HTTP_OK);

          // $ci->set_response($decoded, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $invalid = ['status' => $e->getMessage()]; //Respon if credential invalid
            $this->response($invalid, 401);
             
        }
    }
}
