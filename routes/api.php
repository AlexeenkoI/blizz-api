<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/hearthstone')->group(function (){
    Route::get('metadata', 'HearthstoneController@metaData');
    Route::get('cards', 'HearthstoneController@cards');
});


Route::get('test1', 'HearthstoneController@metaData');
Route::get('test', function (Request $request) {
    $client = new \GuzzleHttp\Client([
        'base_uri' => 'https://eu.battle.net/'
    ]);
    $id = env('BLIZZ_API_CLIENT_ID');
    $secret = env('BLIZZ_API_CLIENT_SECRET');

    try{
        $response = $client->request('POST', 'oauth/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($id . ":" . $secret),
            ],
            'multipart' => [
                [
                    'Content-type' => 'multipart/form-data',
                    'name' => 'grant_type',
                    'contents' => 'client_credentials'
                ]
            ],
        ]);
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        dd($e->getMessage() ,$e->getResponse());
    }

    /** @var \GuzzleHttp\Psr7\Stream $b */
    $responseBody = json_decode($response->getBody()->getContents());

    $dataClient = new \GuzzleHttp\Client([
        'base_uri' => 'https://us.api.blizzard.com/',
        'headers' => [
            'Authorization' => 'Bearer ' . $responseBody->access_token,
        ]
    ]);
    try {
        $testCall = $dataClient->request('GET', 'hearthstone/metadata', [
                'query' => [
                    'locale' => 'ru_RU'
                ]
            ]
        );
    } catch (\GuzzleHttp\Exception\RequestException $ex) {
        dd('err',$ex->getMessage() ,$ex->getResponse());
    }
    dd(json_decode($testCall->getBody()->getContents()));

});
