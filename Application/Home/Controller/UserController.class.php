<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {


	//注册
	public function register(){
		if (!IS_POST) {
			$this->assign('CloseVerify',C('CloseVerify'));
			$this->display ();
		}else{
			$username = I("username");
			$password = I("password");
			$confirm_password = I("confirm_password");
			$v_code = I("v_code");
			if (C('CloseVerify') || $v_code && $v_code == session('v_code') ) {
				if ( $password != '' && $password == $confirm_password) {
			  		if ( ! D("User")->isExist($username) ) {
						//if(preg_match('/^[a-z]+[0-9]*@leqee\.com$/',$username)){
			  			if($this->verifyWithLeqeeAA($username,$password,$error)){
							$ret = D("User")->register($username,$password);
							if ($ret) {
					      			$this->message(L('register_succeeded'),U('Home/User/login'));					    
							}else{
						  		$this->message(L('username_or_password_incorrect'));
							}
						}else{
							$this->message('麻烦用LEQEE ERP账户注册Orz。报错：'.$error);
						}
			  		}else{
			  			$this->message(L('username_exists'));
			  		}
			  	}else{
			  		$this->message(L('code_much_the_same'));
			  	}
			  }else{
				    $this->message(L('verification_code_are_incorrect'));
			  }
		}
	}



	//登录
	public function login()
	{
		if (!IS_POST) {
			//如果有cookie记录，则自动登录
			$cookie_token = cookie('cookie_token');
			if ($cookie_token) {
				$ret = D("UserToken")->getToken($cookie_token);
				if ($ret && $ret['token_expire'] > time() ) {
					D("User")->setLastTime($ret['uid']);
					$login_user = D("User")->where(array('uid' => $ret['uid']))->field('password', true)->find();
					session("login_user" , $login_user);
					$this->message(L('auto_login_succeeded'),U('Home/Item/index'));
					exit();
				}
			}
			$this->assign('CloseVerify',C('CloseVerify'));
		  	$this->display ();

		}else{
		  $username = I("username");
		  $password = I("password");
		  $v_code = I("v_code");
		  if (C('CloseVerify')) { //如果关闭验证码
		  	$ret = D("User")->checkLogin($username,$password);
		    if ($ret) {
		      session("login_user" , $ret );
		      D("User")->setLastTime($ret['uid']);
		      $token = D("UserToken")->createToken($ret['uid']);
	          cookie('cookie_token',$token,60*60*24*90);//此处由服务端控制token是否过期，所以cookies过期时间设置多久都无所谓
		      unset($ret['password']);
	          $this->message(L('login_succeeded'),U('Home/Item/index'));		        
		    }else{
		      $this->message(L('username_or_password_incorrect'));
		    }
		  }else{
			  if ($v_code && $v_code == session('v_code')) {
			    $ret = D("User")->checkLogin($username,$password);
			    if ($ret) {
			      session("login_user" , $ret );
			  D("User")->setLastTime($ret['uid']);
		      	  $token = D("UserToken")->createToken($ret['uid']);
          		  cookie('cookie_token',$token,60*60*24*90);//此处由服务端控制token是否过期，所以cookies过期时间设置多久都无所谓
			      unset($ret['password']);

		          $this->message(L('login_succeeded'),U('Home/Item/index'));		        
			    }else{
			      $this->message(L('username_or_password_incorrect'));
			    }

			  }else{
			    $this->message(L('verification_code_are_incorrect'));
			  }	
		  }
		  

		}
	}

	//生成验证码
	public function verify(){
	  //生成验证码图片
	  Header("Content-type: image/PNG");
	  $im = imagecreate(44,18); // 画一张指定宽高的图片
	  $back = ImageColorAllocate($im, 245,245,245); // 定义背景颜色
	  imagefill($im,0,0,$back); //把背景颜色填充到刚刚画出来的图片中
	  $vcodes = "";
	  srand((double)microtime()*1000000);
	  //生成4位数字
	  for($i=0;$i<4;$i++){
	  $font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255)); // 生成随机颜色
	  $authnum=rand(1,9);
	  $vcodes.=$authnum;
	  imagestring($im, 5, 2+$i*10, 1, $authnum, $font);
	  }
	  $_SESSION['v_code'] = $vcodes;

	  for($i=0;$i<200;$i++) //加入干扰象素
	  {
	    $randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
	    imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); // 画像素点函数
	  }
	  ImagePNG($im);
	  ImageDestroy($im);
	}

	public function setting(){
		$user = $this->checkLogin();
		if (!IS_POST) {
		  $this->assign("user",$user);
		  $this->display ();

		}else{
			$username = $user['username'];
			$password = I("password");
			$new_password = I("new_password");
			$ret = D("User")->checkLogin($username,$password);
			if ($ret) {
					$ret = D("User")->updatePwd($user['uid'],$new_password);
					if ($ret) {
						$this->message(L('modify_succeeded'),U("Home/Item/index"));
					}else{
						$this->message(L('modify_faild'));

					}

				}else{	
					$this->message(L('old_password_incorrect'));
				}

		}
	}

	//退出登录
	public function exist(){
		$login_user = $this->checkLogin();
		session("login_user" , NULL);
		cookie('cookie_token',NULL);
		session(null);
		$this->message(L('logout_succeeded'),U('Home/index/index'));
	}

	private function verifyWithLeqeeAA($username,$password,&$error=''){
		$url=C("AA_URL");
		$fields=array(
			"act"=>"validate_login",
			"data"=>array(
				'username'=>$username,
				'up_checksum'=>md5($username.'#'.md5($password)),
				'ip'=>'',
			)
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			// 'W-ACCESS-TOKEN: '.$access_token,
			'Content-Type: application/json'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// curl_setopt($ch, CURLOPT_COOKIE, implode(';', $cookies_items));
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

		if($method=='PUT'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		}elseif($method=='DELETE'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}

		$data = curl_exec($ch);
		curl_close($ch);

		if($data){
			$data=json_decode($data,true);
			if(!is_array($data)){
				$error='RESPONSE cannot be parsed into standard JSON.';
				return false;
			}
			if(!isset($data['result'])){
				$error='RESPONSE has not RESULT field.';
				return false;
			}
		}else{
			$error='EMPTY AA RESPONSE. CALL SINRI FOR HELP.';
			return false;
		}

		if($data['result']==='OK'){
			return true;
		}
		if(isset($data['data'])){
			$error=$data['data'];
		}else{
			$error='ERROR messages is not given.';
		}
		return false;
	}
}
