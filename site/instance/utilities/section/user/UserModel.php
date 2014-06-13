<?
class UserModel extends CRUDPage{
	public $model = array(
			'table'=>'user'
		);
	function __construct(){
		$this->crud[1] = array(
				'write' => array(
					'actor_id' => function(){return User::actorId();},
					'time_created' => false,
					'is_verified' => 0,
					'is_disabled' => 0
				),
			);
		$this->crud[2] = array(
				'show' => 'send',
				'format' => array('send' => function($v,$item){return '<a href="/messaging/create?actor_id__to='.$item['actor_id'].'">Send Message</a>';}),
				'title' => array('send'=>'Send')
			);
		$this->crud[4] = array(
				'write' => '-time_created',
			);
		$this->crud[5] = array(#update and create
				'write' => array(
					'password' => function($value){return User::hashPassword($value);}
				),
				'format' => '-is_verified&is_disabled',
				'input' => array(
					'is_verified' => array(1=>'Yes',0=>'No'),
					'is_disabled' => array(1=>'Yes',0=>'No'),
				),
				'show' => '-id&-time_created',
				'validate' => array(
					'display_name' => 'f:regexReplace|@[^a-z0-9_]@i,v:lengthRange|3;25',
					'name_first' => 'name',
					'name_last' => 'name',
					'email' => FieldIn::read('email'),
					'website' => 'f:trim,?!v:filled,!v:isUrl',
					'public_statement' => InputHandle::read('basicText','?!v:filled'),
					'password' => '!v:filled'
				),
			);
		$this->crud[8] = array(#manage
			'!show' => 'display_name&email&name_first&name_last&time_created&is_verified&is_disabled',
			'read' => '-website&-public_statement&-password',
			'format' => array(
					'email' => function($value,$item){
						return '<a href="read/'.$item['id'].'">'.$item['email'].'</a>';
					}
				)
		);
		$this->crud[31] = array(#all
				'show' => 'name_first&name_last&email',
				'title' => array(
					'name_first' => 'First Name',
					'name_last' => 'Last Name',
				),
				'format' => array(
					'is_disabled' => array('No','Yes'),
					'is_verified' => array('No','Yes'),
					'password' => array(),
				)
			);
		parent::__construct();
	}
	function delete(){
		parent::delete(array($this,'conditionalDelete'));
	}
	function conditionalDelete(){
		$this->read(CRUDPage::DELETE);
		if(Page::$data->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){
			return i()->CRUDModel($this)->delete();
		}
	}
}