<?
class EventModel extends CRUDPage{
	public $model = array(
			'table'=>'event'
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
				'format' => array(
					'time_start' => function($v,$item){return i()->Time($v,Config::$x['timezone'])->datetime($item['time_zone']);},
					'time_end' => function($v,$item){return i()->Time($v,Config::$x['timezone'])->datetime($item['time_zone']);},
				)
			);
		$this->crud[5] = array(#update and create
				'input' => array(
					'time_zone' => Arrays::at(Time::usaTimezones(),'standard'),
					'time_start'=>array('form'=>'text','options'=>array('time_start',null,array('class'=>'datetimeSelecter'))),
					'time_end'=>array('form'=>'text','options'=>array('time_end',null,array('class'=>'datetimeSelecter'))),
					
				),
				'show' => '-id&-actor_id&-time_created&-time_updated',
				'validate' => array(
					'title' => FieldIn::read('title',null,'!v:filled'),
					'text' => InputHandle::read('basicText'),
					'time_zone' => 'f:trim,!v:filled,!!v:timezone',
					'time_start' => array('f:trim','!v:filled','!!v:date',array('f:toDatetime',&Page::$in['time_zone'])),
					'time_end' => array('f:trim','!v:filled','!!v:date',array('f:toDatetime',&Page::$in['time_zone'])),
					'location' => 'f:trim,f:stripTags',
					'' => 'p:checkTimes'
				),
				'format' => array(
					'text' => function($v){return ViewTool::conditionalBr2Nl($v);},
					'location' => function($v){return ViewTool::conditionalBr2Nl($v);},
				),
			);
		$this->crud[8] = array(#manage
			'!show' => 'actor&title&time_created',
			'format' => array('title' => function($v,$item){return '<a href="/post/read/'.$item['id'].'">'.htmlspecialchars($v).'</a>';})
		);
		$this->crud[10] = array(#manage and manage
			'format' => array(
				'time_start' => function($v,$item){return ViewTool::humanDatetime($v,$item['time_zone']);},
				'time_end' => function($v,$item){return ViewTool::humanDatetime($v,$item['time_zone']);},
				'time_created' => function($v){return ViewTool::datetime($v);},
				'time_updated' => function($v){return ViewTool::datetime($v);},
			)
		);
					
		$this->crud[30] = array(#all but create
			'read' => 'actor.display_name'
		);
		$this->crud[31] = array(#all
				'show' => '-id&-actor_id&title&text',
				'title' => array(
					'actor' => 'Visitor',
					'time_start' => 'Start Date',
					'time_end' => 'End Date'
				),
				'format' => array(
					'actor' => function($value,$item){
						return ViewTool::actor($item['actor.display_name'],$item['actor_id']);
					},
					'time_created' => function($v){return ViewTool::timeAgo($v);},
					'time_updated' => function($v){return ViewTool::timeAgo($v);},
				)
			);
		parent::__construct();
	}
	function create(){
		$return = parent::create();
		Activity::add(4,$return);
		return  $return;
	}
	function delete(){
		return Activity::delete(4,parent::delete(array($this,'conditionalDelete')));
	}
	function conditionalDelete(){
		$this->read(CRUDPage::DELETE);
		if(Page::$data->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){
			return i()->CRUDModel($this)->delete();
		}
	}
	function readCheck(){
		parent::readCheck();
		$this->item['joiners'] = Db::rows('select ej.*, a.display_name
			from event_joiner ej
				left join actor a on ej.actor_id = a.id
			where _id = '.$this->id.' order by time_Created');
		foreach($this->item['joiners'] as $joiner){
			if($joiner['actor_id'] == User::actorId()){
				$this->item['has_joined'] = true;
			}
		}
	}
	function join(){
		if(!$this->item['has_joined']){
			Db::insert('event_joiner',array('_id' => $this->id,'actor_id' => User::actorId(),'time_created'=>i()->Time()));
		}
	}
	function unjoin(){
		if($this->item['has_joined']){
			Db::delete('event_joiner',array('_id' => $this->id,'actor_id' => User::actorId()));
		}
	}
	static function checkTimes(){
		if(i()->Time(Page::$in['time_start'])->unix() >= i()->Time(Page::$in['time_end'])->unix()){
			throw new InputException('Start time must come before end time');
		}
	}
}