<?php

return [
    // sso 地址
    'domain'    => 'http://sso.test',
    // 自己的登录地址
    'login_url' => request()->domain() . '/index/login/index',
];