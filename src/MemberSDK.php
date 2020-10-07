<?php

namespace Tnlmedia\MemberSDK;

class MemberSDK
{
    
    protected $client_id;
    
    protected $client_secret;
    
    protected $redirect_url;

    protected $scopes = [];
    
    protected $stateless = false;

    protected $token;

    protected $stage = false;

    protected $me;

    protected $debug = false;

    /*
     * MemberSDK constructor
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    /*
     * Config init
     */
    private function configure($config)
    {
        
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
     * Switch to production environment
     */
    public function onProduction()
    {
        $this->stage = false;
        return $this;
    }

    /*
     * Switch to stage environment
     */
    public function onStage()
    {
        $this->stage = true;
        return $this;
    }

    /*
     * Enable debug mode
     */
    public function enableDebug()
    {
        $this->debug = true;
        return $this;
    }

    /*
     * Disable debug mode
     */
    public function disableDebug()
    {
        $this->debug = false;
        return $this;
    }
    /*
     * Init
     */
    public function initAccess()
    {
        $this->token = $this->getAccessTokenByCerdentials();
        return $this;
    }

    /*
     * Get token by code
     */
    private function getAccessTokenResponse($code)
    {
        try {
            $api_url = $this->getMemberApiUri() . '/token';
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

        } catch (Exception $e) {
            return $this->resultException($e, null);
        }
    }

    /*
     * Get member url
     */
    private function getMemberUri()
    {
        if (!$this->stage) {
            return 'https://member.tnlmedia.com/';
        }
        return 'https://stage-member.tnlmedia.com/';
    }
    
    /*
     * Get member url
     */
    private function getMemberApiUri()
    {
        if (!$this->stage) {
            return 'https://member.tnlmedia.com/api/v1';
        }
        return 'https://stage-member.tnlmedia.com/api/v1';
    }

    /*
     * Get login auth url
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
        
        $auth_url = $this->getMemberUri() . '?' . http_build_query($fields);
        return $auth_url;
    }

    /*
     * Location login redirect
     */
    public function redirect()
    {
        $auth_url = $this->getAuthUrl();  
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    }
    
    /*
     * Get call code by auth login
     */
    public function callback()
    {
        try {
            if (!isset($_GET['code'])) {
                return false;
            }

            if ($this->hasInvalidState()) {
                return false;
            }

            $this->token = $this->getAccessTokenResponse($_GET['code']);

            $this->me = $this->getMeFromToken($this->token);

            return $this;
        } catch (Exception $e) {
            return $this->resultException($e);
        }
    }

    
    /*
     * Get current user 
     */
    public function getMe() 
    {
        return $this->me;
    }
    
    /*
     * Set token
     */
    public function setToken($token) 
    {
        $this->token = $token;
        return $this;
    }
    
    /*
     * Get token by Cerdentials
     */
    private function getAccessTokenByCerdentials()
    {
        try {
            $api_url = $this->getMemberApiUri() . '/token';
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
                return null;
            }
            curl_close($ch);

            $response = json_decode($response, true);
            if ($response and isset($response['access_token'])) {
                return $response['access_token'];            
            }
        } catch (Exception $e) {
            return $this->resultException($e, null);
        }
    }
    
    /*
     * Get user response by token
     */
    public function getMeFromToken($token) 
    {
        try {
            $api_url = $this->getMemberApiUri() . '/users/me';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                return null;
            }
            curl_close($ch);

            $user_result = json_decode($response, true);
            if ($user_result and $user_result['code'] == 200 and isset($user_result['data'])) {
                return $this->buildUserObj($user_result, ['token' => $token]);
            }
            return null;
        } catch (Exception $e) {
            return $this->resultException($e, null);
        }
    }

    /*
     * Get user data by id
     */
    public function getUserById($id) 
    {
        try {
            
            if (!$this->token) {
                $this->token = $this->getAccessTokenByCerdentials();
            } 

            $api_url = $this->getMemberApiUri() . '/users/' . $id;
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
            }
            return null;
        } catch (Exception $e) {
            return $this->resultException($e, null);
        }
        
    }
    
    /*
     * Build user object
     */
    private function buildUserObj($user_result, $added = [])
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
        if (count($added) > 0) {
            $user = array_merge($user, $added);
        }

        return (object) $user;
    }
    
    
    /*
     * Get token status
     */
    public function getTokenStatus($token)
    {
        try {
            $api_url = $this->getMemberApiUri() . '/token';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                return null;
            }
            curl_close($ch);

            return json_decode($response, true);
        } catch (Exception $e) {
            return $this->resultException($e, null);
        }
    
    }

    /*
     * Patch token status
     */
    public function updateUserStatus($id, $status = 1) 
    {
        try {
            if (!$this->token) {
                $this->token = $this->getAccessTokenByCerdentials();
            } 

            $data = [
                'status' => $status,
                'scope'  => 'user_status',
            ];

            $api_url = $this->getMemberApiUri() . '/users/' . $id . '/status';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                return false;
            }
            curl_close($ch);

            return json_decode($response, true);
        } catch (Exception $e) {
            return $this->resultException($e, null);
        }
    }
    
    /*
     * Search user  
     */
    public function usersSearch($keyword, $options = []) 
    {
        try {
            if (!$this->token) {
                $this->token = $this->getAccessTokenByCerdentials();
            } 
            $data = ['search' => $keyword];

            if (count($options) > 0) {
                $data = array_merge($data, $options);
            } 

            $api_url = $this->getMemberApiUri() . '/users?' . http_build_query($data);
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
            } 
            return null;

        } catch (Exception $e) {
            return $this->resultException($e, null);
        }
        
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
    
    /*
     * Return exception result
     */
    protected function resultException(Exception $e, $result = false)
    {
        if ($this->debug) {
            throw $e;
        }
        return $result;
    }
}