<?php

namespace App\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class remoteAccessToken
 *
 * @package App\Entities
 * @property int $id
 * @property string|null $token
 * @property string $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\RemoteAccessToken whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RemoteAccessToken extends Model
{
    /**
     * @var string
     */
    protected $table = 'remote_access_tokens';

    /**
     * @var array
     */
    protected $fillable = [
        'token',
        'expires_at'
    ];
}
