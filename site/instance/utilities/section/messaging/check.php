<?
class PageTool{
	function check(){
		$count =  Db::row('message',array('actor_id__to'=>User::actorId(),'is_read'=>'0'),'count(*)');
		$cookie = new EncryptedCookie('messages');
		if($cookie->data === null && $count){
			Page::success('You have '.$count.' new message'.($count > 1 ? 's' : ''),null,null,array('expiry'=>15));
			$cookie->data['count'] = $count;
			return array('messages' => Page::$messages, 'count' => $count);
		}
		return (int)$count;
	}
}