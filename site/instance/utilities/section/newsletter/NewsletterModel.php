<?
class NewsletterModel extends CRUDPage{
	public $model = array(
			'table'=>'post'
		);
	function __construct(){
		$this->crud[1] = array(
				'write' => array(
					'actor_id' => function(){return User::actorId();},
					'time_created' => false,
					'time_updated' => false,
				),
			);
		$this->crud[2] = array(#manage
			'show' => 'actor',
			);
		
		$this->crud[4] = array(
				'write' => '-id&-time_created&-actor_id',
				'@write' => array('time_updated' => 0),
			);
		$this->crud[5] = array(#update and create
				'show' => '-id&-actor_id&-time_created&-time_updated',
				'validate' => array(
					'title' => FieldIn::read('title',null,'!v:filled'),
					'text' => InputHandle::read('basicText'),
				),
				'format' => array(
					'text' => function($v){return ViewTool::conditionalBr2Nl($v);},
				),
			);
		$this->crud[8] = array(#manage
			'!show' => 'actor&title&time_created',
			'format' => array('title' => function($v,$item){return '<a href="/post/read/'.$item['id'].'">'.htmlspecialchars($v).'</a>';})
		);
		$this->crud[10] = array(#read & manage
				'format' => array(
					'is_public' => array(1=>'Yes',0=>'No'),
					'time_created' => function($v){return ViewTool::datetime($v);},
					'time_updated' => function($v){return ViewTool::datetime($v);}
				),
			);
		$this->crud[30] = array(#all but create
			'read' => 'actor.display_name'
		);
		$this->crud[31] = array(#all
				'show' => '-id&-actor_id&title&text',
				'title' => array(
					'actor' => 'Visitor',
				),
				'format' => array(
					'actor' => function($value,$item){
						return ViewTool::actor($item['actor.display_name'],$item['actor_id']);
					},
				)
			);
		parent::__construct();
	}
	function create(){
		$return = parent::create();
		Activity::add(3,$return);
		return  $return;
	}
	function delete(){
		return Activity::delete(3,parent::delete(array($this,'conditionalDelete')));
	}
	function conditionalDelete(){
		$this->read(CRUDPage::DELETE);
		if(Page::$data->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){
			return i()->CRUDModel($this)->delete();
		}
	}
}