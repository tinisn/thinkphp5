<?php

namespace app\common\model;

use houdunwang\crypt\Crypt;
use think\Loader;
use think\Model;
use think\Validate;

class Admin extends Model
{
    //
    protected $pk = 'admin_id'; //主键
    //设置当前模型对应的完整数据表名称
    protected $table = 'ws_admin';
    /**
     * 登录
     */
    public function login($data){
    	// halt($data);
    	//1.执行验证
    	$validate = Loader::validate('Admin');
    	//验证不通过
		if(!$validate->check($data)){
			return ['valid' => 0, 'msg' => $validate->getError()];
		}
    	//2.比对用户名密码是否正确
    	$userInfo = $this->where('admin_username', $data['admin_username'])->where('admin_password', Crypt::encrypt($data['admin_password']))->find();
    	// halt($userInfo);
    	if (!$userInfo) {
    		//在数据库未匹配到相关数据
    		return ['valid' => 0, 'msg' => '用户或密码不正确'];
    	}
    	//3.将用户信息存入到session中
    	session('admin.admin_id', $userInfo['admin_id']);
    	session('admin.admin_username', $userInfo['admin_username']);
    	return ['valid' => 1, 'msg' => '登录成功'];

    }

    /**
     * 修改密码
     * @param $data post数据
     */
    public function pass($data){
    	//1.执行验证
    	$validate = new Validate([
    		'admin_password' => 'require',
    		'new_password' => 'require',
    		'confirm_password' => 'require|confirm:new_password'
    		],[
    		'admin_password.require' => '请输入原始密码',
    		'new_password.require' => '请输入新密码', 
    		'confirm_password.require' => '请确认新密码',
    		'confirm_password.confirm' => '确认密码与新密码不一致'
    		]);
    	if (!$validate->check($data)) {
    		return ['valid' => 0, 'msg' => $validate->getError()];
    		// dump($validate->getError());
    	}
    	//2.原始是否正确
    	$userInfo = $this->where('admin_id', session('admin.admin_id'))->where('admin_password', Crypt::encrypt($data['admin_password']))->find();
    	if (!$userInfo) {
    		return ['valid' => 0, 'msg' => '原始密码不正确'];
    	}
    	//3.修改密码
    	//save方法第二个参数为更新条件
    	$res = $this->save([
    		'admin_password' => Crypt::encrypt($data['new_password'])],
    			[$this->pk => session('admin.admin_id')]);
    	// halt($res);
    	if ($res) {
    		return ['valid' => 1, 'msg' => '密码修改成功'];
    	}else{
    		return ['valid' => 0, 'msg' => '密码修改失败'];
    	}
    	 
    }
}
