<?php

namespace Mengdodo\Ngpopen\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Action
{
    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    public static function api($method, $post)
    {
        $config = config('ngp');
        $header['app_secret'] = $config['AppSecret'];
        $header['app_key'] = $config['AppKey'];
        $header['timestamp'] = self::getTimestamp();
        $header['token'] = (new Auth($config))->access_token();
        $header['method'] = $method;

        $sign = self::getSign($header, $post);
        Log::debug('sign: ' . $sign);
        $host = $config['AppUrl'];
        $url = $host . '/rest?timestamp=' . $header['timestamp'] . '&app_key=' . $header['app_key'] . '&method=' . urlencode($header['method']) . '&sign=' . urlencode($sign) . '&token=' . $header['token'];
        Log::debug('请求接口: ' . $url);
        Log::debug('post参数: ', $post);
        $client = new Client();
        $response = $client->post($url, ['json' => $post, 'headers' => ['Content-Type' => 'application/json', 'charset' => 'utf-8']]);
        $res = (string)$response->getBody();
        Log::debug('接口响应: ' . $res);
        return json_decode($res, true);
    }

    protected static function getSign($header, $post): string
    {
        $app_secret = $header['app_secret'];
        unset($header['app_secret']);
        ksort($header);
        Log::debug('sign参数: ', $header);
        $sign_arr = [];
        foreach ($header as $k => $v) {
            $sign_arr[] = $k . $v;
        }
        $sign_str = implode('', $sign_arr) . json_encode($post);
        Log::debug('拼装字符串: ' . $sign_str);
        Log::debug('使用secret: ' . $app_secret);
        return hash_hmac('md5', $sign_str, $app_secret);
    }

    protected static function getTimestamp(): int
    {
        $microseconds = explode(' ', microtime());
        return (int)sprintf('%d%03d', $microseconds[1], $microseconds[0] * 1000);
    }
}
