<?
class CandidateModel extends CRUDPage{
	public $model = array(
			'table'=>'candidate'
		);
	function __construct(){
		$this->crud[1] = array(
				'write' => array(
					'actor_id' => function(){return User::actorId();},
					'time_created' => false,
					'is_endorsed' => 0,
					'has_signed_contract' => 0
				),
			);
		$this->crud[4] = array(
				'write' => '-id&-time_created&-is_public&-actor_id',
				'show' => '-is_public'
			);
		$this->crud[5] = array(#update and create
				'show' => '-id&-actor_id&-time_created&-is_endorsed&-has_signed_contract',
				'validate' => array(
					'name_first' => 'name',
					'name_last' => 'name',
					'email' => FieldIn::read('email'),
					'desired_position' => InputHandle::read('basicText'),
					'location' => InputHandle::read('basicText'),
					'qualifications' => InputHandle::read('basicText'),
					'personal_support' => InputHandle::read('basicText'),
					'about' => InputHandle::read('basicText'),
					'notes' => InputHandle::read('basicText','?!v:filled'),
				),
				'format' => array(
					'desired_position' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'location' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'qualifications' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'personal_support' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'about' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'notes' => function($v){return ViewTool::conditionalBr2Nl($v);},
				),
			);
		$this->crud[8] = array(#manage
			'show' => '-skills&-time_available&-notes&-about&-personal_support&-qualifications&-location&-desired_position',
			'format' => array(
					'email' => function($value,$item){
						return '<a href="read/'.$item['id'].'">'.$item['email'].'</a>';
					}
				)
		);
		$this->crud[10] = array(#read & manage
			'show' => '-id&-actor_id',
			'format' => array(
				'time_created' => function($v){return ViewTool::datetime($v);},
				'time_updated' => function($v){return ViewTool::datetime($v);},
			),
		);
		$this->crud[30] = array(#all but create
			'read' => 'actor.display_name'
		);
		$this->crud[31] = array(#all
				'show' => 'name_first&name_last&email',
				'title' => array(
					'name_first' => 'First Name',
					'name_last' => 'Last Name',
					'time_available' => 'Availability',
					'actor.display_name' => 'Visitor',
					'personal_support' => '<span data-help="Do you have family and friends that would support your candidacy?  Please detail your personal support">Personal Support</span>',
					'desired_position' => '<span data-help="Please detail the position you want to run for">Desired Position</span>',
					'location' => '<span data-help="Please detail where you are wanting to run">Location</span>',
					'has_signed_contract' => '<span data-help="Whether candidate has signed and had notarized the QLParty contract">Has Signed Contract</span>',
					'is_endorsed' => '<span data-help="Whether QLParty has endorsed this candidate">Is Endorsed</span>',
				),
				'format' => array(
					'actor' => function($value,$item){
						return ViewTool::actor($item['actor.display_name'],$item['actor_id']);
					},
					//'has_signed_contract' => array('No','Yes'),
					'has_signed_contract' => array('No','Yes'),
					'is_endorsed' => array('No','Yes'),
				)
			);
		parent::__construct();
	}
	function create(){
		$return = parent::create();
		Activity::add(1,$return);
		return  $return;
	}
	function delete(){
		return Activity::delete(1,parent::delete(array($this,'conditionalDelete')));
	}
	function conditionalDelete(){
		$this->read(CRUDPage::DELETE);
		if(Page::$data->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){
			return i()->CRUDModel($this)->delete();
		}
	}
}