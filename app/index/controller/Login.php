<?php
declare (strict_types=1);

namespace app\index\controller;

use app\BaseController;
use Exception;
use think\exception\HttpResponseException;

class Login extends BaseController
{
    public function initialize()
    {
        // 已登录
        if (session('?user') && request()->action() !== 'logout') {
            // 抛出 http 异常
            throw new HttpResponseException(redirect('/'));
        }
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        if (request()->isPost() && request()->isAjax()) {
            // 获取数据
            $data = request()->only([
                'username' => null
            ], 'post', 'trim');
            // 验证数据
            if (empty($data['username'])) {
                return json(['code' => 400, 'msg' => '请输入用户名']);
            }
            // 执行登录
            $token = rpcClient('Login')->login($data);
            $token = json_decode($token, true);

            if (isset($token['code']) && $token['code'] == 200) {
                // 登录成功
                session('user', [
                    'id'       => $token['id'],
                    'username' => $data['username'],
                    'token'    => $token['token'],
                ]);

                $url = config('sso.domain') . '/sso/login/index?url=' . config('sso.login_url') . '&token=' . $token['token'];

                return json(['code' => 200, 'msg' => '登录成功', 'url' => $url]);
            } else {
                // 登录失败
                return json(['code' => 400, 'msg' => '登录失败']);
            }
        }

        return view();
    }

    public function logout()
    {
        session(null);

        return json(['code' => 200, 'msg' => '登出成功！', 'url' => config('sso.domain') . '/sso/login/logout?url=' . config('sso.login_url')]);
    }
}
