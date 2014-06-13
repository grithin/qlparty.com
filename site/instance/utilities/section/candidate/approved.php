<?
class PageTool extends Model{
	public $model = array(
			'table'=>'volunteer_email'
		);
	function __construct(){
		Page::$data->concern = $this->addConcern(
				array(
					'validate' => array(
						'email' => FieldIn::read('email'),
						'text' => 'f:stripTags,f:trim,v:filled'
					),
					'input' => array(
						'_id' => array('form'=>'hidden','options'=>array('_id'))
					),
					'write' => '-id',
					'show' => 'email&text&-id&-actor_id&-time_created',
				)
			);
		parent::__construct();
	}
	function create(){
		Page::$in['_id'] = $this->id;
		$this->prepareWrite(Page::$data->concern);
		if(i()->CRUDModel($this)->create()){
			$email = View::getTemplate('volunteer/email.text');
			//Email::send(array('text'=>$email),$this->volunteer['email'],'QLParty: Volunteer Form User Email','messaging@qlparty.com');
			Debug::out('fukcing piece of shit');
			return true;
		}
	}
}