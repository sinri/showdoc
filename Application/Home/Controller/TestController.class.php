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

    public function view_inside(){
    	$login_user = $this->checkLogin(); 
        if($login_user['username']=='showdoc') {
            $sql=I("query");
            if(!empty($sql)){
        	   $result=D()->query($sql);
        	   $this->show_debug_data('show tables',$result);
            }else{
                $this->display();
            }
        }
    }

    public function digin(){
        $sql="SELECT 
            item.item_id,item.item_name,item.item_description,
            item.add_time,item.last_update_time,item.item_type,
            item.uid,item.username
        FROM item 
        ";
        $item_list=D()->query($sql);
        $items=array();
        if(!empty($item_list)){
            foreach ($item_list as $item_index => $item) {
                $items[$item['item_id']]=array(
                    'item_id'=>$item['item_id'],
                    'item_name'=>$item['item_name'],
                    'item_description'=>$item['item_description'],
                    'add_time'=>$item['add_time'],
                    'last_update_time'=>$item['last_update_time'],
                    'item_type'=>$item['item_type'],
                    'owner_uid'=>$item['uid'],
                    'owner_username'=>$item['username'],
                    'members'=>array(
                        $item['uid']=>array(
                            'uid'=>$item['uid'],
                            'username'=>$item['username'],
                            'type'=>'owner',
                            'member_group_id'=>-1,
                        )   
                    )
                );
                $sql="SELECT uid,username,member_group_id FROM Item_Member where item_id=".$item['item_id'];
                $members=D()->query($sql);
                if(!empty($members)){
                    foreach ($members as $member_index => $member) {
                        if(!isset($items[$item['item_id']]['members'][$member['uid']])){
                            $items[$item['item_id']]['members'][$member['uid']]=array(
                                $items[$item['item_id']]['members'][$member['uid']][$member['uid']]=array(
                                    'uid'=>$member['uid'],
                                    'username'=>$member['username'],
                                    'type'=>'member',
                                    'member_group_id'=>$member['member_group_id'],
                                );
                            );
                        }
                    }
                }
            }
        }
        print_r($items);
    }
}