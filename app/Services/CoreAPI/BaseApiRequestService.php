<?php

namespace App\Services;

use App\Entities\RemoteAccessToken;
use App\Services\CoreAPI\AccessTokenService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class BaseApiRequestService
{
    const UNAUTHORIZED_CODE = 401;

    /** @var Client */
    protected $authClient;

    /** @var AccessTokenService */
    protected $accessTokenService;

    public function __construct(AccessTokenService $accessTokenService)
    {
        $this->authClient = new Client([
            'base_uri' => env('BLUZZ_AUTH_URL')
        ]);
        $this->accessTokenService = $accessTokenService;
    }

    /**
     * @throws GuzzleException
     * @return RemoteAccessToken
     */
    protected function getAuthToken()
    {
        $apiKey = env('BLIZZ_API_CLIENT_ID');
        $apiSecret = env('BLIZZ_API_CLIENT_SECRET');
        $response = null;
        try {
            $res = $this->authClient->request('POST', 'oauth/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($apiKey . ":" . $apiSecret),
                ],
                'multipart' => [
                    [
                        'Content-type' => 'multipart/form-data',
                        'name' => 'grant_type',
                        'contents' => 'client_credentials'
                    ]
                ],
            ]);

            $responseBody = $this->getResponseContent($res);
            $response =  $this->accessTokenService->create($responseBody);

        } catch (RequestException $requestException) {
            //TODO исключения
            dd($requestException->getResponse());
        }

        return $response;
    }

    protected function getResponseContent(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

}
