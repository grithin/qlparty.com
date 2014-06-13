<?
class PageTool{
	static function validate(){
		Page::filterAndValidate(array(
			'name_first' => 'name',
			'name_last' => 'name',
			'email' => 'email,!p:emailCheck',
			'password' => 'password',
			'display_name' => 'name,!p:displayNameCheck',
			'agree' => '!v:filled'
		));
		return !Page::errors();
	}
	
	function create(){
		if($this->validate()){
			$insert = Arrays::extract(array('email','password','display_name','name_first','name_last'),Page::$in);
			$insert['time_created'] = i()->Time()->datetime();
			$insert['password'] = sha1($insert['password']);
			$id = Db::insert('user',$insert);
			
			//add to email verfiication table
			$verificationCode = Tool::randomString(20,'#[a-z0-9]#');
			Db::insert('user_email_verification',array(
					'time_created' => i()->Time()->datetime(),
					'user_id' => $id,
					'email' => $insert['email'],
					'code' => $verificationCode,
				));
			
			//add ip to user ip table
			$clientIpId = Db::id('client_ip',array('ip'=>Http::getIp()));
			Db::insert('user_ip',array(
					'client_ip_id' => $clientIpId,
					'time_created' => i()->Time()->datetime(),
					'time_updated' => i()->Time()->datetime(),
					'_id' => $id,
				));
			
			Page::$data->name = $insert['display_name'];
			Page::$data->code = $verificationCode;
			Page::$data->userId = $id;
			
			Email::send(View::getTemplate('user/signup.email'),$insert['email'],'Signup Successful','confirm@'.$_SERVER['HTTP_HOST']);
			return true;
		}
	}
	static function emailCheck($value){
		if(Db::check('user',array('email' => $value))){
			throw new InputException('There is already a user with the email address you entered');
		}
	}
	static function displayNameCheck($value){
		if(Db::check('user',array('display_name' => $value))){
			throw new InputException('There is already a user with the display name you entered');
		}
	}
}
