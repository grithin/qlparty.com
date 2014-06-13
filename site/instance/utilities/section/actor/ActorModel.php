<?
class ActorModel extends CRUDPage{
	public $model = array(
			'table'=>'actor'
		);
	function __construct(){
		$this->crud[2] = array(
				'show' =>'message&-id&-time_updated',
				'read' => 'user.id.id',
				'format' => array('message'=>function($v,$item){return '<a href="/messaging/create?actor_id__to='.$item['id'].'">Send Message</a>';}),
				'title' => array('message' => 'Message'),
			);
		parent::__construct();
	}
}