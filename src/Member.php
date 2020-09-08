<?php

namespace Tnlmedia\MemberSDK;

class Member
{
    protected $api_uri;
    
    protected $client_id;
    
    protected $client_secret;
    
    protected $redirect_url;

    protected $scopes = [];
    
    protected $stateless = false;

    protected $token;

    protected $auth_uri;

    public function __construct($config = [])
    {
        $this->configure($config);
    }

    /*
     * config init
     */
    private function configure($config)
    {
        if (isset($config['auth_uri'])) {
            $this->auth_uri = $config['auth_uri'];
        }
        
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
        
        if (isset($config['scopes'])) {
            $this->scopes = $config['scopes'];
        }
    }
    
    /*
     * get token by code
     */
    private function getAccessTokenResponse($code)
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
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        
        $response = json_decode($response, true);
        if ($response and isset($response['access_token'])) {
            return $response['access_token'];            
        }
        return null;
    }

    /*
     * get login auth url
     */
    public function getAuthUrl() 
    {
        $state = null;

        if ($this->usesState()) {
            @session_start();
            $_SESSION['state'] = $state = $this->getState();
        }
        
        $fields = [
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_url,
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        $auth_url = $this->auth_uri . '?' . http_build_query($fields);
        return $auth_url;
    }

    /*
     * login redirect
     */
    public function redirect()
    {
        $auth_url = $this->getAuthUrl();  
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    }
    
    /*
     * get user by auth login
     */
    public function user()
    {
        if (!isset($_GET['code'])) {
            return false;
        }

        if ($this->hasInvalidState()) {
            return false;
        }
        
        if (!$this->token) {
            $this->token = $this->getAccessTokenResponse($_GET['code']);
        }

        $user_result = $this->getUserByToken($this->token);
        if ($user_result and $user_result['code'] == 200 and isset($user_result['data'])) {
            return array_merge($this->buildUserObj($user_result), ['token' => $this->token]);
        } else {
            return null;
        }
    }

    /*
     * build user object
     */
    private function buildUserObj($user_result)
    {
        $user = [
            'id'       => $user_result['data']['id'],
            'nickname' => $user_result['data']['nickname'] ?? null,
            'avatar'   => $user_result['data']['avatar'] ?? null,
            'email'    => (isset($user_result['data']['mail']) and isset($user_result['data']['mail']['value'])) ? $user_result['data']['mail']['value'] : null,
            'mobile'   => (isset($user_result['data']['mobile']) and isset($user_result['data']['mobile']['value'])) ? $user_result['data']['mobile']['value'] : null,
            'language' => $user_result['data']['language'] ?? null,
            'timezone' => $user_result['data']['timezone'] ?? null,
            'status'   => $user_result['data']['status'] ?? null,
            'created'  => $user_result['data']['created'] ?? null,
        ];

        return $user;
    }
    
    /*
     * set token
     */
    public function setToken($token) 
    {
        $this->token = $token;
        return $this;
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
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        return json_decode($response, true);
    }

    /*
     * get user data by id
     */
    public function getUserById($id) 
    {
        if (!$this->token) {
            $this->token = $this->getAccessTokenByCerdentials();
        } 
        
        $api_url = $this->api_uri . '/users/' . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        $user_result = json_decode($response, true);
        if ($user_result and $user_result['code'] == 200 and isset($user_result['data'])) {
            return $this->buildUserObj($user_result);
        } else {
            return null;
        }
        
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

        if (count($this->scopes) > 0) {
            $post_data['scope'] = implode(' ', $this->scopes);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        
        $response = json_decode($response, true);
        if ($response and isset($response['access_token'])) {
            return $response['access_token'];            
        }
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
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        return json_decode($response, true);
    
    }

    /*
     * patch token status
     */
    public function patchUserStatus($id, $status = 1) 
    {
        if (!$this->token) {
            $this->token = $this->getAccessTokenByCerdentials();
        } 
        
        $data = [
            'status' => $status,
        ];
        
        $api_url = $this->api_uri . '/users/' . $id . '/status';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        return json_decode($response, true);
    }
    
    /*
     * search user  
     */
    public function usersSearch($keyword, $options = []) 
    {
        if (!$this->token) {
            $this->token = $this->getAccessTokenByCerdentials();
        } 
        $data = ['search' => $keyword];
        
        if (count($options) > 0) {
            $data = array_merge($data, $options);
        } 

        $api_url = $this->api_uri . '/users?' . http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        $users_result = json_decode($response, true);
        if ($users_result and $users_result['code'] == 200 and isset($users_result['data'])) {
            return $users_result['data'];
        } else {
            return null;
        }
        return $user_result;
        
    }
    
    /**
     * Set the scopes of the requested access.
     */
    public function setScopes(array $scopes)
    {
        $this->scopes = array_unique(array_merge($this->scopes, $scopes));

        return $this;
    }
    /**
     * Determine if the current request / session has a mismatching "state".
     */
    protected function hasInvalidState()
    {
        if ($this->isStateless()) {
            return false;
        }

        @session_start();

        $state = $_SESSION['state'];
        
        return ! (strlen($state) > 0 && $_GET['state'] === $state);
    }

    /**
     * Determine if the provider is operating with state.
     */
    protected function usesState()
    {
        return ! $this->stateless;
    }   

    /**
     * Determine if the provider is operating as stateless.
     */
    protected function isStateless()
    {
        return $this->stateless;
    }

    /**
     * Indicates that the provider should operate as stateless.
     */
    public function stateless()
    {
        $this->stateless = true;

        return $this;
    }
    
    /**
     * Get the string used for session state.
     */      
    protected function getState()
    {
        return md5(mt_rand());
    }

}
