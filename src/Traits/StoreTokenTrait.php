<?php
namespace Mengdodo\Ngpopen\Traits;

use Illuminate\Support\Facades\Cache;

trait StoreTokenTrait
{
    public function storeToken($res){
        Cache::set('npg_access_token', $res['data']['access_token'], $res['data']['expires_in'] - 3600);
        Cache::set('npg_refresh_token', $res['data']['refresh_token'], $res['data']['re_expires_in'] - 3600);
        Cache::set('npg_profile_id', $res['data']['profile_id'], $res['data']['re_expires_in'] - 3600);
        return $res;
    }
}
