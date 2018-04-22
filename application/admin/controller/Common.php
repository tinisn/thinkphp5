<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    //构造函数
    public function __construct(Request $request = null){
    	parent::__construct($request);
    	//执行登录验证
    	// $_SEEION['admin']['admin_id']
    	if (!session('admin.admin_id')) {
    		$this->redirect('admin/Login/login');
    	}
    }
}
