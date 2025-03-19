<?php

namespace Mengdodo\Ngpopen\Library;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Mengdodo\Ngpopen\Traits\StoreTokenTrait;

class Auth
{
    use StoreTokenTrait;

    protected array $config;
    public function __construct($config)
    {
        $this->config = $config;
    }
    /**
     * @throws Exception
     */
    public function access_token($code = null)
    {
        if($code) return $this->createToken($code);

        $token = Cache::get('npg_access_token');
        if ($token) return $token;

        $refresh_token = Cache::get('npg_refresh_token');
        if (!$refresh_token)
            throw new Exception('管家婆刷新refresh_token不存在');

        return $this->_refreshAccessToken($refresh_token);
    }

    /**
     * @throws Exception
     */
    protected function createToken($code): \Illuminate\Http\JsonResponse
    {
        $url = $this->config['AppUrl'] . '/oauth2/token?app_key=' . $this->config['AppKey'] . '&app_secret=' . $this->config['AppSecret'] . '&grant_type=authorization_code&code=' . $code;
        try {
            $client = new Client();
            $response = $client->post($url, ['verify' => false]);
            $res = (string)$response->getBody();
            $res = json_decode($res, true);

            if (!isset($res['data']['access_token']))
                throw new Exception(json_encode($res));

            $this->storeToken($res);
            return response()->json($res);
        } catch (GuzzleException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @throws Exception
     */
    protected function _refreshAccessToken($refresh_token){
        $url = $this->config['AppUrl'] . '/oauth2/token?app_key=' . $this->config['AppKey'] . '&app_secret=' . $this->config['AppSecret'] . '&grant_type=refresh_token&refresh_token=' . $refresh_token;
        try {
            $client = new Client();
            $response = $client->post($url, ['verify' => false]);
            $res = (string)$response->getBody();
            $res = json_decode($res, true);

            if (!isset($res['code']) || $res['code'] == -1)
                throw new Exception($res['message']);

            $this->storeToken($res);
            return $res['data']['access_token'];
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
