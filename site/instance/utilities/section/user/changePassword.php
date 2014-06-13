<?
class PageTool{
	function validate(){
		Page::filterAndValidate(array(
			'current' => 'password,p:checkPassword',
			'new' => 'password',
			'' => '!p:checkMatch',
		));
		return !Page::errors();
	}
	
	function update(){
		if($this->validate()){
			Db::update('user',array('password'=>sha1(Page::$in['new'])),User::id());
			return true;
		}
	}
	static function checkPassword($value){
		$passwordCheck = Db::check('user',
			array('id' => User::id(),
				'password' => sha1($value)));
		if(!$passwordCheck){
			throw new InputException('Current passssword incorrect');
		}
	}
	static function checkMatch($old){
		if(Page::$in['new'] != Page::$in['newAgain']){
			throw new InputException('Passwords don\'t match');
		}	
	}
}
