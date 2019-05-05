<?php
// +----------------------------------------------------------------------
// | layerIM + workerman + ThinkPHP5 即时通讯
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\index\controller;

use think\Controller;

class Register extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    
    public function doRegister()
    {
    	$uname = input('param.username');
        $uname = htmlspecialchars($uname);

    	$userinfo = db('chatuser')->where('username', $uname)->find();

    	if( !empty($userinfo) ){
    		$this->error('此账号已注册');
    	}

        $pwd = input('param.pwd');
        $pwd = htmlspecialchars($pwd);
        if (!$pwd) {
            $this->error("请输入密码");
        }


        db('chatuser')->insert(['username'=>$uname,'pwd'=>md5($pwd)]);

        $this->redirect(url('login/index'));
    	
    }
    
}
