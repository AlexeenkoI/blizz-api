<?php
declare(strict_types=1);

namespace App\Services\CoreAPI;

use App\Entities\RemoteAccessToken;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class AccessTokenService
{
    public function create(array $args)
    {
        $token = RemoteAccessToken::where('token', '=', $args['access_token'])->first() ?: new RemoteAccessToken();

        $token->token = $args['access_token'];
        $token->expires_at = Carbon::now()->add(CarbonInterval::seconds($args['expires_in'])->toDateInterval());
        $token->save();
        return $token;
    }

    public function get(string $id)
    {
       return RemoteAccessToken::all();
    }

    public function getLastToken()
    {
        return RemoteAccessToken::latest()->first() ?: '';
    }

    public function getItem(string $id)
    {
        return RemoteAccessToken::findOrFail($id);
    }

    public function delete(string $id)
    {
        $token = RemoteAccessToken::findOrFail($id);
        $token->delete();
        return $token;
    }
}
