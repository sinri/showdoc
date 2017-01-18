<?php
namespace Home\Model;
use Home\Model\BaseModel;

class LeqeeAdminModel extends BaseModel {

	public function _initialize(){
		$sql="PRAGMA table_info(leqee_admin);";
		$result=D()->query($sql);
		if(empty($result)){
			$sql="CREATE TABLE leqee_admin(
				uid INT PRIMARY KEY NOT NULL,
				username TEXT NOT NULL,
				level TEXT NULL
			);";
			D()->execute($sql);
			$sql="INSERT INTO leqee_admin(uid,username,level) values(1,'showdoc','admin');";
			D()->query($sql);
		}
	}

	public function isAdmin($uid,&$level=null){
		$level=null;
		$record=D("LeqeeAdmin")->where(array("uid"=>$uid))->find();
		// echo $this->getLastSql();var_dump($record);
		if(empty($record)){
			return false;
		}else{
			$level=$record['level'];
			return true;
		}
	}

	public function setAdmin($uid){
		$user_info=D("User")->userInfo($uid);
		if(empty($user_info)){
			return false;
		}
		$data=array('uid'=>$uid,'username'=>$user_info['username'],'level'=>'admin');
		$this->add($data);
	}

	public function unsetAdmin($uid){
		$user_info=D("User")->userInfo($uid);
		if(empty($user_info)){
			return false;
		}
		if($user_info['username']=='showdoc'){
			return false;
		}
		$this->where(array('uid'=>$uid))->delete();
	}
}