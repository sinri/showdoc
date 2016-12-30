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
			foreach ($users as $key => $value) {
				$users[$key]['reg_time']=date('Y-m-d H:i:s',$value['reg_time']);
				$users[$key]['last_login_time']=date('Y-m-d H:i:s',$value['last_login_time']);
			}
			$this->assign('users',$users);
			$this->display();
		}
	}
	public function digin(){
        $sql="SELECT 
            item.item_id,item.item_name,item.item_description,
            item.addtime,item.last_update_time,item.item_type,
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
                    'addtime'=>date('Y-m-d H:i:s',$item['addtime']),
                    'last_update_time'=>($item['last_update_time']!=0?date('Y-m-d H:i:s',$item['last_update_time']):'-'),
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
                                'uid'=>$member['uid'],
                                'username'=>$member['username'],
                                'type'=>'member',
                                'member_group_id'=>$member['member_group_id'],
                            );
                        }
                    }
                }
            }
        }
        // $this->show_debug_data('All items',$items);
        $this->assign('items',$items);
        $this->display();
    }
}