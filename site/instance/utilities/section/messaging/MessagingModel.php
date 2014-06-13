<?
/**
Overview
	parts
		create: actor_id_to expected pre-input
		manage: include both to and from messages.  No update abillity.
	Require
		actor name link to pages allowing messaging
		js message checker to alert on new messages
		link to messaging in nav (with counter or read)
		read on read by actor to
	
*/
class MessagingModel extends CRUDPage{
	public $model = array(
			'table'=>'message'
		);
	function __construct(){
		$this->crud[1] = array(
				'write' => array(
					'actor_id__from' => function(){return User::actorId();},
					'time_created' => false,
					'is_read' => 0,
				),
				'input' => array(
					'actor_id__to' => function($field,$item){return '<a href="/actor/read/'.$item['id'].'">'.$item['display_name'].'</a>';}
				),
				'show' => '-id&-actor_id__from&-time_created&-is_read',
				'validate' => array(
					'title' => 'title',
					'actor_id__to' => 'f:trim,!v:filled,!v:existsInTable|actor',
					'text' => InputHandle::read('basicText'),
				),
				'title' => array(
					'actor_id__to' => 'To'
				),
				'format' => array(
					'text' => function($v){return ViewTool::conditionalBr2Nl($v);},
				),
			);
		$this->crud[8] = array(#manage
			'show' => '-text',
			'read' => '-text',
			'format' => array(
					'title' => function($v,$item){
						return '<a href="/messaging/read/'.$item['id'].'">'.$v.'</a>';
					},
				)
		);
		$this->crud[10] = array(#read & manage
			'format' => array(
					'actor_id__to' => function($value,$item){
						return '<a href="/actor/read/'.$value.'">'.$item['actor.actor_id__to.display_name'].'</a>';
					},
					'actor_id__from' => function($value,$item){
						return '<a href="/actor/read/'.$value.'">'.$item['actor.actor_id__from.display_name'].'</a>';
					},
					'is_read' => array('No','Yes'),
					'time_created' => function($v){return ViewTool::datetime($v);}
				),
			'show' => '-id',
			'title' => array(
				'is_read' => 'Read',
				'actor.actor_id__to.display_name' => 'To',
				'actor.actor_id__from.display_name' => 'From',
			),
			'read' => 'actor.actor_id__to.display_name&actor.actor_id__from.display_name'
		);
		
		parent::__construct();
	}
	function delete(){
		parent::delete(array('PageTool','conditionalDelete'));
	}
	function conditionalDelete(){
		$this->read(CRUDPage::DELETE);
		if(Page::$data->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){
			return i()->CRUDModel($this)->delete();
		}
	}
	function readCheck(){
		$return = parent::readCheck();
		if(!$this->item['is_read'] && $this->item['actor_id__from'] == User::actorId()){
			Db::update('message',array('is_read'=>1),$this->id);
			$this->item['is_read'] = 1;
		}
		return $return;
	}
}