<?php

namespace app\system\controller;

use think\Controller;
use think\Db;

class Component extends Controller
{
    //
    public function uploader(){
    	// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		// halt($info);
		if($info){
			// 成功上传后 获取上传信息
			$data = [
				'name' 		=> input('post.name'),
				'filename' 	=> $info->getFilename(),
				'path' 		=> str_replace('\\', '/', 'uploads/' . $info->getSaveName()),
				'extension' => $info->getExtension(),
				'createtime'=> time(),
				'size'		=> $info->getSize(),
			];
			Db::name('attachment')->insert($data);
			echo json_encode(['valid' => 1, 'message' => str_replace('\\', '/', WS_ROOT . 'uploads/' . $info->getSaveName())]);
		}else{
			// 上传失败获取错误信息
			echo json_encode(['valid' => 0, 'message' => $file->getError()]);
		}
    }

    public function filesLists(){
    	$db = Db::name('attachment')->whereIn('extension', explode(',', strtolower(input('post.extensions'))))->order('id desc');
    	$res = $db->paginate(2);
    	$data = [];
    	if ($res->toArray()) {
    		foreach ($res as $k => $v) {
    			$data[$k]['createtime'] = date('Y-m-d', $v['createtime']);
    			$data[$k]['size'] 		= $v['size'];
    			$data[$k]['url']		= WS_ROOT . $v['path'];
    			$data[$k]['path']		= WS_ROOT . $v['path'];
    			$data[$k]['name']		= $v['name'];
    		}
    	}
    	echo json_encode(['valid'=>1,'data' => $data,'page'=>$res->render()?$res->render():'']);
    }
}
