<?php


namespace App\Services\Hearthstone;


use App\Entities\RemoteAccessToken;
use App\Services\BaseApiRequestService;
use App\Services\CoreAPI\AccessTokenService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class HsApiRequestService extends BaseApiRequestService
{
    /** @var Client */
    private $client;

    public function __construct(AccessTokenService $accessTokenService)
    {
        parent::__construct($accessTokenService);

        $this->client = $this->initClient();
    }

    private function initClient()
    {
        /** @var RemoteAccessToken $tokenInstance */
        $tokenInstance = $this->accessTokenService->getLastToken();
        $token = $tokenInstance ? $tokenInstance->token : '';
        return new Client([
            'base_uri' => 'https://us.api.blizzard.com/hearthstone/',
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param string $uri
     * @param array $data
     * @return mixed|null
     * @throws GuzzleException
     */
    public function get(string $uri, array $data = [])
    {
        return $this->processRequest($uri, $data);
    }

    /**
     * @param $uri
     * @param array $data
     * @param string $method
     * @param bool $repeatable
     * @return mixed|null
     * @throws GuzzleException
     */
    protected function processRequest($uri, $data = [], $method = 'get', $repeatable = true)
    {
        $response = null;
        try {
            $res = $this->client->{$method}($uri, $data);
            $response = $this->getResponseContent($res);
        } catch (RequestException $exception) {
            if ($repeatable) {
                if ($exception->getCode() == self::UNAUTHORIZED_CODE) {
                    $this->getAuthToken();
                    return $this->processRequest($uri, $data, $method, false);
                }
            }
            dd('что-то не так', $exception->getCode(), $exception->getMessage());
        }
        return $response;
    }

}
