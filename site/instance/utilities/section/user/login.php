<?
class pageTool{
	static $user;
	
	function validate(){
		Page::filterAndValidate(array(
			'email' => 'email',
			'password' => 'password',
			'' => '!p:loginCheck'
		));
		return !Page::errors();
	}
	
	function update(){
		if($this->validate()){
			User::login(self::$user['id'],self::$user['actor_id'],self::$user['display_name']);
			if(User::hasPrivilege('general admin')){
				$_SESSION['isAdmin'] = true;
			}
			return true;
		}
	}
	static function loginCheck(){
		if(Page::errors()){
			return;
		}
		$user = Db::row('user',array(
					'email' => Page::$in['email'],
					'password' => sha1(Page::$in['password'])
				),
			'id,actor_id,is_disabled,is_verified,display_name');
		if(!$user){
			throw new InputException('Email - Password combo not found');
		}elseif($user['is_disabled']){
			throw new InputException('User account is disabled');
		}elseif(!$user['is_verified']){
			throw new InputException('User account email needs verification');
		}
		self::$user = $user;
	}
}
