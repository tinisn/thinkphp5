<?php

namespace app\admin\controller;

use app\common\model\Admin;
use houdunwang\crypt\Crypt;
use think\Controller;

class Login extends Controller
{
    //
    public function login(){
    	//echo Crypt::encrypt('tin1211'); //加密 zFIb5EwT4sdk6L0CVpCzeA==
    	// echo Crypt::decrypt('zFIb5EwT4sdk6L0CVpCzeA==');
    	//测试数据库链接
    	// $data = db('admin')->find(1);
    	// dump($data);
    	if (request()->isPost()) {
    		// halt($_POST);
    		$res = (new Admin())->login(input('post.'));
    		if ($res['valid']) {
    			//登录成功
    			$this->success($res['msg'], 'admin/Entry/index');
    		}else{
    			//登录失败
    			$this->error($res['msg']);
    		}
    	}
    	//加载登录页面
    	return $this->fetch();
    }
}
