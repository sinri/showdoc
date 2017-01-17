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

	public function isAdmin($username,&$level=null){
		$level=null;
		$record=D("LeqeeAdmin")->where(array("username"=>$username))->find();
		// echo $this->getLastSql();var_dump($record);
		if(empty($record)){
			return false;
		}else{
			$level=$record['level'];
			return true;
		}
	}

	public function setAdmin($uid,$username){
		$data=array('uid'=>$uid,'username'=>$username,'level'=>'admin');
		$this->add($data);
	}

	public function unsetAdmin($uid){
		$this->where(array('uid'=>$uid))->delete();
	}
}