<?
class PageTool{
	static $data;
	static function validate(){
		Page::filterAndValidate(array(
			'id' => '!!v:filled',
			'code' => '!!v:filled',
			'' => 'p:checkCode'
		));
		return !Page::errors();
	}
		
	static function verify(){
		if(self::validate()){
			$user = Db::row('user',self::$data['user_id']);
			
			if(!$user['actor_id']){
				$actorId = Db::insert('actor',array(
						'display_name' => $user['display_name'],
						'time_created' => i()->Time(),
						'time_updated' => i()->Time()
					));
				Db::update('user',array('actor_id' => $actorId),$user['id']);
			}
			
			Db::update('user_email_verification',array('time_verified'=>i()->Time()->datetime()),self::$data['id']);
			Db::update('user',
					array(
						'is_verified'=>1,
						'email' => self::$data['email'],
					),
					self::$data['user_id']
				);
			User::login(self::$data['user_id'],$user['actor_id'],$user['display_name']);
			
			Page::success('Email Verified');
			return true;
		}
	}
	static function checkCode(){
		self::$data = Db::row('user_email_verification',array(
				':time_verified' => null,
				'user_id' => Page::$in['id'],
				'code' => Page::$in['code']
			));
		if(!self::$data){
			throw new InputException('Verification failed; you may have already verified');
		}
		
	}
}
