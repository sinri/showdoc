<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends BaseController {
	protected $login_user=false;
	protected function _initialize(){
		$this->login_user = $this->checkLogin(); 
        if(!$this->login_user || $this->login_user['username']!='showdoc') {
        	$this->message('闲人莫入 (╯‵□′)╯︵┻━┻');
        	exit();
        }
	}
	/////
	public function users(){
		$sql="SELECT * FROM user";
		$users=D()->query($sql);
		if(empty($users)){
			$this->message('没有用户。那你是谁啊。');
		}else{
			$this->assign('users',$users);
			$this->display();
		}
	}
}