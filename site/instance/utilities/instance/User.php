<?
class User{
//+	basic login stuff {
	static function login($id,$actorId,$name){
		Session::create();
		Session::$other = array('user_id'=>$id);
		$_SESSION['userId'] = $id;
		$_SESSION['actorId'] = $actorId;
		$_SESSION['displayName']=$name;
		
		$clientIpId = Db::row('client_ip',array('ip' => Http::getIp()),'id');
		Db::insertUpdate('user_ip',array(
				'_id'=>$id,
				'client_ip_id'=>$clientIpId,
				'time_created'=>i()->Time(),
				'time_updated' => i()->Time(),
			),array(
				'time_updated' => i()->Time(),
				':duration' => 'duration + 1'	
			));
		
		Db::update('user',array('time_last_login'=>i()->Time()->datetime()),$id);
	}
	static function logout(){
		//log action
		session_destroy();
	}
	
	///ensure user is logged in
	static function required(){
		if(!$_SESSION['userId']){
			Cookie::set('url',$_SERVER['REQUEST_URI']);
			Http::redirect('/user/login');
		}
	}
	///returns id of current user - easier to remember than the session variable
	static function id(){
		return $_SESSION['userId'];
	}
//+	}
//+	basic user and group privilege handling{
	static function isAdmin(){
		return $_SESSION['isAdmin'];
	}
	static $userGroups;
	static $userGroupPrivileges;
	static $userPrivileges;
	static function getUserGroups($userId=null){
		$userId = $userId ? $userId : $_SESSION['userId'];
		if(!isset(self::$userGroups[$userId])){
			self::$userGroups[$userId] = Db::column('user_group_user',array('__id'=>$userId),'_id');
		}
		return self::$userGroups[$userId];
	}
	static $groupIds;
	static function inGroup($name,$user=null){
		if(!self::$groupIds[$name]){
			self::$groupIds[$name] = Db::row('user_group',array('name'=>$name),'id');
		}
		$groups = self::getUserGroups($user);
		return in_array(self::$groupIds[$name],$groups);
	}
	///Check if user has a privilege
	/**
	@param	privilege	either int id or string name
	@param	userId	the user to check.  Defaults to current user
	*/
	static function hasPrivilege($privilegeName,$userId=null){
		if(!$userId){
			$userId = $_SESSION['userId'];
			if(!$userId){
				return false;
			}
		}
		//privilege can be given as either the id or the name
		if(!Tool::isInt($privilege)){
			$privilegeId = self::getPrivilege($privilegeName);
		}
		
		if(!isset($userPrivileges[$userId][$privilegeId])){
			$groups = self::getUserGroups();
			if($groups){
				//check the users groups to see if they give user the privilege
				foreach($groups as $group){
					if(!isset(self::$userGroupPrivileges[$group])){
						self::$userGroupPrivileges[$group] = Db::columnKey('__privilege_type_id','user_group_privilege',array('_id'=>$group),'__privilege_type_id,id');
					}
					if(self::$userGroupPrivileges[$group][$privilegeId]){
						$userPrivileges[$userId][$privilegeId] = true;
						break;
					}
				}
			}
		}
		//check if the privilege is given directly to the user
		if(!isset($userPrivileges[$userId][$privilege])){
			$userPrivileges[$userId][$privilege] = Db::row('user_privilege',array('_id'=>$userId,'type_id'=>$privilege),'1');
		}
		
		return $userPrivileges[$userId][$privilege];
	}
	static $privileges;
	static function getPrivilege($name){
		if(!self::$privileges[$name]){
			self::$privileges[$name] = Db::row('user_privilege_type',array('name'=>$name),'id');
		}
		return self::$privileges[$name];
	}
	static function requirePrivilege($privilege){
		if(!self::hasPrivilege($privilege)){
			die('You are not authorized for: '.$privilege);
		}
	}
	static $notAuthorizedNote = '';
	static function notAuthorized($note=null){
		if(Config::$x['notAuthorizedPage']){
			self::$notAuthorizedNote = $note;
			View::end(Config::$x['notAuthorizedPage']);
		}else{
			echo $note; exit;
		}
	}
//+	}
//+	basic user action logging {
	static $tables;
	///Check if user has a privilege
	/**
	@param	table	name of table
	@param	type	insert, delete, update
	@param	rowData	the data used on update, insert
	@param	$rowId	the row id
	*/
	static function logTableChange($table,$type,$rowData=null,$rowId=null){
		if(!$rowId){
			$rowId = $rowData['id'];
		}
		$tableId = self::getTableId($table);
		$typeId = Db::row('user_log_table_change_type',array('name'=>$type),'id');
		
		Db::insert('user_log_table_change',array(
			'user_id' => $_SESSION['userId'],
			'table_id' => $tableId,
			'row_id' => $rowId,
			'type_id' => $typeId,
			'time' => i()->Time()->datetime(),
			'change' => serialize($rowData)
		));
	}
	static function getTableId($name){
		$tableId = Db::row('table_type',array('name'=>$name),'id');
		if(!$tableId){
			$display = ucwords(preg_replace('@_@',' ',$name));
			$tableId = Db::insert('table_type',array('name'=>$name,'display'=>$display));
		}
		return $tableId;
	}
	static function logAction($action,$data=null,$user=null){
		if(!$user){
			$user = $_SESSION['userId'];
		}
		$action = self::getAction($action);
		
		Db::insert('user_log_action',array(
				'user_id' => $user,
				'action_id' => $action,
				'time' => i()->Time()->datetime(),
				'data' => $data,
			));
	}
	static $actions;
	static function getAction($name){
		if(!self::$actions[$name]){
			self::$actions[$name] = Db::row('user_log_action_type',array('name'=>$name),'id');
		}
		return self::$actions[$name];
	}
//+	}
//+	basic user account functions
	static function hashPassword($password){
		return md5($password);
	}
	static function changePassword($current,$new){
		if(!Db::row('select 1 from user where password = '.Db::quote(self::hashPassword($current)).' and id = '.Db::quote($_SESSION['userId']))){
			Page::$errors[] = 'Incorrect password entered';
		}else{
			Db::update('user',array('password'=>self::hashPassword($new)),$_SESSION['userId']);
			Page::$messages[] = 'Password updated';
		}
	}
	static function disable($userId){
		Db::delete('session',array('user_id'=>$userId));
		Db::update('user',array('is_disabled'=>1),$userId);
	}
//+	}
	static function actorId(){
		return $_SESSION['actorId'];
	}
}
