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

class Index extends Controller
{
	public function _initialize()
	{
		if( empty(cookie('uid')) ){
			$this->redirect( url('login/index'), 302 );
		}
        // $this->user_img = '/static/admin/images/a3.jpg';
        $this->admin_img = '/static/admin/images/a5.jpg';
	}
	
    public function index()
    {
    	$mine = db('chatuser')->where('id', cookie('uid'))->find();
    	$this->assign([
    			'uinfo' => $mine,
                'user_img' => $this->admin_img
    	]);

        return $this->fetch();
    }
    
    //获取列表
    public function getList()
    {
    	//查询自己的信息
        $mine = db('chatuser')->where('id', cookie('uid'))->find();
        $user = db('chatuser')->select();

        $group[] = [ 'groupname' => '客服列表', 'id' => 1, 'online' => false, 'list' => [] ];
        foreach ($user as $key => $val)
        {
            if ($val['id'] !== $mine['id']) {
                $group[0]['list'][] = [
                    'username' => $val['username'],
                    'id' => $val['id'],
                    'status' => $val['status'],
                    'avatar' => $this->admin_img
                ];
            }
            
        }
        //组合返回数据
        $return = [
            'code' => 0,
            'msg'=> '',
            'data' => [
                'mine' => [
                    'username' => $mine['username'],
                    'id' => $mine['id'],
                    'status' => $mine['status'],
                    'avatar' => $this->admin_img,
                    'sign'  =>'目前客服列表是所有已注册用户'
                ],
                'friend' => $group,//分组
                'group' => ''
            ],
        ];

        return json( $return );

    }
    

}
