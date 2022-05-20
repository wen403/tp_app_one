<?php

namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'tp1主页面用户' . session('user.username');
    }

}
