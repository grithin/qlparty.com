<?
class PageTool{
	static $model = array('table'=>'feedback');
	static $id;
	function validate(){
		Page::filterAndValidate(array(
			'text' => 'f:trim,!v:filled,!v:lengthRange|20;10000',
		));
		return !Page::errors();
	}
	
	function create(){
		if($this->validate()){
			Email::send(array('text'=>'From User '.User::id().':'.Page::$in['text']),'adam_qlparty@deemit.com','QLParty User Feedback','feedbacker@qlparty.com');
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
