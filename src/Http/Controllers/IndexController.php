<?php

namespace Mengdodo\Ngpopen\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mengdodo\Ngpopen\Library\Auth;

class IndexController extends Controller
{
    private array $config;

    public function __construct(){
        $this->config = config('ngp');
    }

    function getAuth(): string
    {
        return $this->config['AppUrl'] . '/oauth2/authorize?app_key=' . $this->config['AppKey'] . '&response_type=code&state=' . $this->config['AppSecret'] . '&redirect_uri=' . route('ngp.index.code');
    }

    /**
     * @throws \Exception
     */
    public function code(Request $request): \Illuminate\Http\JsonResponse
    {
        $code = $request->input('code');
        if (!$code) return response()->json(['message' => 'code不存在', 'code' => 4001], 500);

        $res = (new Auth($this->config))->access_token($code);
        return is_string($res) ? response()->json($res) : $res;
    }


    public function access_token(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!config('app.debug')) dd('拒绝访问');
        $res = (new Auth($this->config))->access_token();
        return response()->json($res);
    }
}
