<?
if(in_array(strtolower(RequestHandler::$unparsedUrlTokens[0]),array('create','update','read','manage'))){
	Hook::add('newCRUDPage',function($CRUDPage){
			if(!User::hasPrivilege('admin')){
				$CRUDPage->append('-id&-actor_id&-is_disabled&-is_verified&-time_last_login','show',31);
				$CRUDPage->append('-actor_id&-is_disabled&-is_verified','write',4);
				
			}
		},array('deleteAfter'=>1));
}