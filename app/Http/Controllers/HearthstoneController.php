<?php

namespace App\Http\Controllers;

use App\Services\Hearthstone\HsApiRequestService;
use Illuminate\Http\Request;

class HearthstoneController extends Controller
{
    private $httpService;

    public function __construct(HsApiRequestService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function metaData(Request $request)
    {
            return $this->httpService->get('metadata', $request->query());
    }

    public function cards(Request $request)
    {
        return $this->httpService->get('cards', array_merge($request->query(), [
            'query' => [
                'locale' => 'ru_RU',
                'pageSize' => 20,
                'page' => 1
            ]
        ]));
    }
}
