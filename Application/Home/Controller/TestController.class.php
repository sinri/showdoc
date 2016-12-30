<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends BaseController {
    //项目列表页
    public function index(){
        $login_user = $this->checkLogin(); 
		$this->show_debug_data('user_login',$login_user);

        $items  = D("Item")->where("uid = '$login_user[uid]' or item_id in ( select item_id from ".C('DB_PREFIX')."item_member where uid = '$login_user[uid]' ) ")->select();
        $this->show_debug_data("user's items",$items);

        $items  = D("Item")->where("1=1")->select();
        $this->show_debug_data("all items",$items);
    }

    private function show_debug_data($title,$data){
    	echo "<h3>".$title."</h3>".PHP_EOL;
    	echo "<pre>";
    	print_r($data);
    	echo "</pre>";
    	echo PHP_EOL;
    }

    public function runDB(){
    	$login_user = $this->checkLogin(); 

    	$result=D()->query('show tables');
    	$this->show_debug_data('show tables',$result);
    }
}