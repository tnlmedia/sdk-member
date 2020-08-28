<?php

namespace Tnlmedia\Member;

class Member
{
    protected $api_uri;
    protected $client_id;
    protected $client_secret;
    protected $redirect_url;
    protected $scope;

    public function __construct($config = [])
    {
        $this->configure($config);
        $this->scope = 'user_basic user_profile user_connect';
    }

    private function configure($config)
    {
        if (isset($config['api_uri'])) {
            $this->api_uri = $config['api_uri'];
        }
        
        if (isset($config['client_id'])) {
            $this->client_id = $config['client_id'];
        }
        
        if (isset($config['client_secret'])) {
            $this->client_secret = $config['client_secret'];
        }
        
        if (isset($config['redirect_url'])) {
            $this->redirect_url = $config['redirect_url'];
        }
    }
    
    /*
     * get token by code
     */
    public function getAccessTokenResponse($code)
    {
        $api_url = $this->api_uri . '/token';
        $post_data = [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri'  => $this->redirect_url,
            'code'          => $code,
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
       
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if ($response and isset($response['access_token'])) {
            return $response['access_token'];            
        }
        return null;
    }

    /*
     * login redirect
     */
    public function redirect() 
    {
        $fields = [
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_url,
        ];

        return 'https://greenroom-lista-web3.tnlmedia.com/?'. http_build_query($fields);
    }
    
    /*
     * get user by auth login
     */
    public function user()
    {
        $token = $this->getAccessTokenResponse(request()->input('code'));
        
        $user = $this->getUserByToken($token);
        return $user;
    }

    /*
     * get user data by id
     */
    public function getUserById($id) 
    {
        $token = $this->getAccessTokenByCerdentials();
        
        $api_url = $this->api_uri . '/users/' . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        
        return $response;
        
    }

    /*
     * get user response by token
     */
    protected function getUserByToken($token) 
    {
        $api_url = $this->api_uri . '/users/me';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }
    
    /*
     * get token by Cerdentials
     */
    public function getAccessTokenByCerdentials()
    {
        $api_url = $this->api_uri . '/token';
        $post_data = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if ($response and isset($response['access_token'])) {
            return $response['access_token'];            
        }
        return null;
    }
    
    /*
     * get token status
     */
    public function getTokenStatus($token)
    {
        $api_url = $this->api_uri . '/token';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        
        return $response;
    
    }

    /*
     * patch token status
     */
    public function patchUserStatus($id, $status = 1) 
    {
        $token = $this->getAccessTokenByCerdentials();
        
        $data = ['status' => $status];
        
        $api_url = $this->api_uri . '/users/' . $id . '/status';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        
        return $response;
        
    }
}
