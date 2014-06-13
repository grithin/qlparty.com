<?
class PageTool extends CRUDPage{
	public $model = array(
			'table'=>'newsletter_subscriber'
		);
	function __construct(){
		$this->crud[1] = array(
				'write' => array(
					'actor_id' => function(){return User::actorId();},
					'time_created' => false,
					'status' => 0,
					'ip' => Http::getIp(0),
				),
				'validate' => array(
					'email' => 'v:isEmailLine',
					'' => 'p:noDuplication'
				),
			);
		
		parent::__construct();
	}
	function create(){
		if(parent::create()){
			$this->code = Tool::randomString(20,'#[a-z0-9]#');
			$this->item = Db::row('newsletter_subscriber',$this->id);
			Cache::set('emailVerify-'.$this->id,$this->code);
			Email::send(View::getTemplate('newsletter/subscribe.email'),Page::$in['email'],'Signup Successful','confirm@'.$_SERVER['HTTP_HOST']);
			return true;
		}
	}
	function verify(){
		$verification = Cache::get('emailVerify-'.Page::$in['id']);
		if(Page::$in['code'] == $verification){
			Db::update('newsletter_subscriber',array('status'=>1),Page::$in['id']);
			return true;
		}
		
	}
	static function noDuplication(){
		if(Db::check('newsletter_subscriber',array('email' => Page::$in['email']))){
			throw new InputException('Email already in database.  If you did not receive a confirmation email, please wait  a day and submit your email again');
		}
		if(Db::check('newsletter_subscriber',array('actor_id' => User::actorId()))){
			throw new InputException('You\'ve alread added an email');
		}
		if(Db::check('newsletter_subscriber',array('ip' => Http::getIp(0)))){
			throw new InputException('You\'ve alread added an email');
		}
	}
}