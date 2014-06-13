<?
class VolunteerModel extends CRUDPage{
	public $model = array(
			'table'=>'volunteer'
		);
	function __construct(){
		$this->crud[1] = array(
				'write' => array(
					'actor_id' => function(){return User::actorId();},
					'time_created' => false,
				),
				'input' => array(
					'is_public' => array('form'=>'select','options'=>array('is_public',array(1=>'Yes',0=>'No')))
				)
			);
		$this->crud[4] = array(
				'write' => '-id&-time_created&-is_public&-actor_id',
				'show' => '-is_public'
			);
		$this->crud[5] = array(#update and create
				'show' => 'name_first&name_last&email&-id&-actor_id&-time_created',
				'validate' => array(
					'' => 'p:namePresent',
					'name_first' => FieldIn::read('name','?!v:filled'),
					'name_last' => FieldIn::read('name','?!v:filled'),
					'email' => FieldIn::read('email'),
					'skills' => InputHandle::read('basicText'),
					'time_available' => InputHandle::read('basicText'),
					'notes' => InputHandle::read('basicText','?!v:filled'),
					'location' => InputHandle::read('basicText','?!v:filled'),
					'is_public' => 'f:toBool'
				),
				'format' => array(
					'skills' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'time_available' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'notes' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'location' => function($v){return ViewTool::conditionalBr2Nl($v);},
				),
			);
		$this->crud[8] = array(#manage
			'show' => '-skills&-time_available&-notes&-location',
			'format' => array(
					'email' => function($value,$item){
						return '<a href="read/'.$item['id'].'">'.$item['email'].'</a>';
					}
				)
		);
		$this->crud[10] = array(#read & manage
			'format' => array(
				'is_public' => array(1=>'Yes',0=>'No'),
				'time_created' => function($v){return ViewTool::datetime($v);},
				'time_updated' => function($v){return ViewTool::datetime($v);}
			),
			'show' => '-id&-actor_id'
		);
		$this->crud[30] = array(#all but create
			'read' => 'actor.display_name'
		);
		$this->crud[31] = array(#all
				'title' => array(
					'name_first' => 'First Name',
					'name_last' => 'Last Name',
					'is_public' => '<span data-help="Allows others to see your volunteer submission (excluding your email), and allows others to send you messages through the website">Is Public</span>',
					'actor.display_name' => 'Visitor'
				),
				'format' => array(
					'actor' => function($value,$item){
						return ViewTool::actor($item['actor.display_name'],$item['actor_id']);
					}
				)
			);
		parent::__construct();
	}
	static function namePresent(){
		if(!Page::$in['name_first'] && !Page::$in['name_last']){
			 throw new InputException('Must have at least one part of the name');
		 }
	}
	function create(){
		if($return = parent::create()){
			//if public, add to activity
			if(Page::$in['is_public']){
				Activity::add(2,$return);
			}
			return $return;
		}
	}
	function delete(){
		return Activity::delete(2,parent::delete(array($this,'conditionalDelete')));
	}
	function conditionalDelete(){
		$this->read(CRUDPage::DELETE);
		if(Page::$data->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){
			return i()->CRUDModel($this)->delete();
		}
	}
}