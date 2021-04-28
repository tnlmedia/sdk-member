<?php

namespace TNLMedia\MemberSDK;

use Throwable;
use TNLMedia\MemberSDK\Clients\AuthorizeClient;
use TNLMedia\MemberSDK\Clients\Client;
use TNLMedia\MemberSDK\Clients\FlagClient;
use TNLMedia\MemberSDK\Clients\ServiceClient;
use TNLMedia\MemberSDK\Clients\UserClient;
use TNLMedia\MemberSDK\Exceptions\AuthorizeException;
use TNLMedia\MemberSDK\Exceptions\DuplicateException;
use TNLMedia\MemberSDK\Exceptions\Exception;
use TNLMedia\MemberSDK\Exceptions\FormatException;
use TNLMedia\MemberSDK\Exceptions\NotFoundException;
use TNLMedia\MemberSDK\Exceptions\ProtectedException;
use TNLMedia\MemberSDK\Exceptions\RequestException;
use TNLMedia\MemberSDK\Exceptions\RequireException;
use TNLMedia\MemberSDK\Exceptions\UnnecessaryException;
use TNLMedia\MemberSDK\Exceptions\UploadException;
use TNLMedia\MemberSDK\Helpers\Helper;
use TNLMedia\MemberSDK\Helpers\RedirectHelper;
use TNLMedia\MemberSDK\Nodes\AccessToken;

/**
 * Class MemberSDK
 * @package TNLMedia\MemberSDK
 * @property AuthorizeClient $authorize
 * @property UserClient $user
 * @property ServiceClient $service
 * @property FlagClient $flag
 * @property RedirectHelper $redirect
 */
class MemberSDK
{
    /**
     * Belongs console
     *
     * @var int
     */
    protected $console = 0;

    /**
     * Client ID
     *
     * @var int
     */
    protected $client_id = 0;

    /**
     * Secret
     *
     * @var string
     */
    protected $client_secret = '';

    /**
     * OAuth redirect uri
     *
     * @var string
     */
    protected $redirect_uri = '';

    /**
     * API host
     *
     * @var string
     */
    protected $host = 'member.tnlmedia.com';

    /**
     * API version
     *
     * @var string
     */
    protected $version = '1';

    /**
     * Clients
     *
     * @var array
     */
    protected $clients = [];

    /**
     * Helpers
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Client token
     *
     * @var AccessToken
     */
    protected $token;

    /**
     * MemberSDK constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        switch ($config['environment'] ?? '') {
            case 'stage':
                $this->useStage();
                break;

            case 'production':
            default:
                $this->useProduction();
                break;
        }

        if (isset($config['version'])) {
            $this->changeVersion(strval($config['version']));
        }

        if (isset($config['console_id'])) {
            $this->setConsole(intval($config['console_id']));
        }

        if (isset($config['client_id']) && isset($config['client_secret'])) {
            $this->setClient(intval($config['client_id']), strval($config['client_secret']));
        }

        if (isset($config['redirect_uri'])) {
            $this->setDefaultRedirect(strval($config['redirect_uri']));
        }

        if (isset($config['access_token'])) {
            if ($config['access_token'] instanceof AccessToken) {
                $this->setToken($config['access_token']);
            } elseif (is_string($config['access_token'])) {
                $this->setTokenString($config['access_token']);
            }
        }
    }

    /**
     * Property magic
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        // Get client
        if ($client = $this->getClient($name)) {
            return $client;
        }

        // Get helper
        if ($helper = $this->getHelper($name)) {
            return $helper;
        }

        // Error
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property: ' . get_class($this) . '::$' . $name .
            ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    /**
     * Initial client into property and return
     *
     * @param string $name
     * @return mixed|null
     */
    protected function getClient(string $name)
    {
        // Check exists
        $key = strtolower($name);
        if (isset($this->clients[$key])) {
            return $this->clients[$key];
        }

        // Check class
        $namespace = __NAMESPACE__ . '\\Clients\\' . ucfirst($key) . 'Client';
        if (!is_subclass_of($namespace, Client::class)) {
            return null;
        }

        // Initial
        $this->clients[$key] = new $namespace($this);
        return $this->clients[$key];
    }

    /**
     * Initial helper into property and return
     *
     * @param string $name
     * @return mixed|null
     */
    protected function getHelper(string $name)
    {
        // Check exists
        $key = strtolower($name);
        if (isset($this->helpers[$key])) {
            return $this->helpers[$key];
        }

        // Check class
        $namespace = __NAMESPACE__ . '\\Helpers\\' . ucfirst($key) . 'Helper';
        if (!is_subclass_of($namespace, Helper::class)) {
            return null;
        }

        // Initial
        $this->helpers[$key] = new $namespace($this);
        return $this->helpers[$key];
    }

    /**
     * Use production environment
     *
     * @return $this
     */
    public function useProduction()
    {
        $this->host = 'member.tnlmedia.com';
        return $this;
    }

    /**
     * Use stage environment
     *
     * @return $this
     */
    public function useStage()
    {
        $this->host = 'stage-member.tnlmedia.com';
        return $this;
    }

    /**
     * Current host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * API request version
     *
     * @param string $version
     * @return $this
     */
    public function changeVersion(string $version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Set belongs console
     *
     * @param int $console_id
     * @return $this
     */
    public function setConsole(int $console_id)
    {
        $this->console = $console_id;
        return $this;
    }

    /**
     * Get belongs console ID
     *
     * @return int
     */
    public function getConsoleID()
    {
        return $this->console;
    }

    /**
     * Set client
     *
     * @param int $client_id
     * @param string $client_secret
     * @return $this
     */
    public function setClient(int $client_id, string $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        return $this;
    }

    /**
     * Get client ID
     *
     * @return int
     */
    public function getClientID()
    {
        return $this->client_id;
    }

    /**
     * Get client secret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * Set oauth default redirect uri
     *
     * @param string $uri
     * @return $this
     */
    public function setDefaultRedirect(string $redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;
        return $this;
    }

    /**
     * Get oauth redirect uri
     *
     * @return string
     */
    public function getDefaultRedirect()
    {
        return $this->redirect_uri;
    }

    /**
     * Set client token
     *
     * @param AccessToken $token
     * @return $this
     */
    public function setToken(AccessToken $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Set client token via string
     *
     * @param string $token_string
     * @return $this
     */
    public function setTokenString(string $token_string)
    {
        // Simple token
        $token = new AccessToken([
            'expires_in' => 600,
            'access_token' => $token_string,
        ], $this);
        $this->token = $token;
        return $this;
    }

    /**
     * Get current token
     *
     * @return AccessToken|null
     */
    public function getToken()
    {
        if ($this->token instanceof AccessToken) {
            if (!$this->token->isExpire()) {
                return $this->token;
            }
        }
        return null;
    }

    /**
     * Send API request
     *
     * @param string $path
     * @param array $parameters
     * @param string $method
     * @return array|mixed
     * @throws AuthorizeException
     * @throws DuplicateException
     * @throws Exception
     * @throws FormatException
     * @throws NotFoundException
     * @throws ProtectedException
     * @throws RequestException
     * @throws RequireException
     * @throws UnnecessaryException
     * @throws UploadException
     */
    public function request(string $path, array $parameters = [], string $method = 'GET')
    {
        try {
            // Request
            $client = new \GuzzleHttp\Client();
            $result = $client->request($method, $this->buildRequestUrl($path), [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->getToken() ? (string)$this->getToken() : null,
                ],
                'json' => (strtolower($method) != 'get') ? $parameters : null,
                'query' => (strtolower($method) == 'get') ? $parameters : null,
                'timeout' => 30,
                'http_errors' => false,
            ]);

            // Check status
            if ($result->getStatusCode() != 200) {
                throw new RequestException('', $result->getStatusCode());
            }

            // Response
            $response = json_decode($result->getBody()->getContents(), true);
            if (!array_key_exists('code', $response)) {
                return $response;
            }
            if ($response['code'] == 200) {
                return $response['data'] ?? [];
            }

            // Check code
            switch ($response['code'] ?? 0) {
                case 40001:
                    $exception = new RequireException();
                    break;

                case 40002:
                    $exception = new FormatException();
                    break;

                case 40003:
                    $exception = new DuplicateException();
                    break;

                case 40004:
                    $exception = new UnnecessaryException();
                    break;

                case 40101:
                    $exception = new AuthorizeException();
                    break;

                case 40401:
                    $exception = new NotFoundException();
                    break;

                case 42301:
                    $exception = new ProtectedException();
                    break;

                case 50001:
                    $exception = new UploadException();
                    break;

                case 50000:
                default:
                    $exception = new Exception('', intval($response['code'] ?? 0));
                    break;
            }
            $exception->setMessage($response['message'] ?? '');
            $exception->setHint($response['hint'] ?? '');
            throw $exception;
        } catch (Exception $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new Exception($e->getMessage(), 0, $e);
        }
    }

    /**
     * Build request url
     *
     * @param string $path
     * @return string
     */
    protected function buildRequestUrl(string $path)
    {
        $url = 'https://' . $this->host;
        $url .= '/api/v' . $this->version;
        $url .= '/' . trim($path, '/');
        return $url;
    }
}
