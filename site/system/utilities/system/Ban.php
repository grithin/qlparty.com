<?
/**
Expects tables:
	ban
		id,	identity, type_id, time_expire, time_created
	ban_types
		id, name, reason, time_span, limit, ban_time

*/
class Ban{
	/// named instances
	static $instances = array();
	/// Name of the primary instance
	static $primaryName = 0;
	/// object representing the primary instance
	static $primary;
	
	static function __callStatic($name,$arguments){
		return call_user_func_array(array(self::$primary,$name),$arguments);
	}
	function __call($method,$arguments){
		if(method_exists($this->db,$method)){
			$sql = $this->select($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4]);
			return call_user_func(array($this->db,$method),$sql);
		}
		Debug::throwError(__class__.' Method not found: '.$name);
	}
	///singleton construction
	/*
	@param	identity	identity to identify banning info
	**/
	static function initialize($identity=null){
		$identity = $identity ? $identity : $_SERVER['REMOTE_ADDR'];
		if(self::$instances[$identity]){
			return self::$instances[$identity];
		}else{
			$class = __class__;
			self::$instances[$identity] = new $class($identity);
			//set primary if no instances except this one
			if(count(self::$instances) == 1){
				self::setPrimary($identity);
			}
			return self::$instances[$identity];
		}
	}
	///sets primary to some named model object.  Can bypass and directly set $primary if object is not named
	static function setPrimary($name){
		self::$primaryName = $name;
		self::$primary = self::$instances[$name];//php already assigns objects by reference
	}
	static $banTypes;
	private function __construct($identity){
		if(mt_rand(1,200) === 1){
			self::maintenance();
		}
		$this->identity = $identity;
		$bans = unserialize(Cache::get('bans-'.$this->identity));
		if($bans){
			$this->banned($bans);
		}
		self::$banTypes = unserialize(Cache::get('table_ban_types'));
		if(!self::$banTypes){
			self::$banTypes = Db::columnKey('name','ban_type','1=1');
		}
	}
	///clears cached bans, but not db bans
	static function clearBans($identity=null){
		$identity = $identity ? $identity : $_SERVER['REMOTE_ADDR'];
		Cache::delete('bans-'.$identity);
		Cache::delete('banning-'.$identity);
	}
	///load bans from db
	static function maintenance(){
		$rows = Db::rows('select identity, bt.reason, b.time_expire
			from ban b
				left join ban_type bt on b.type_id_ = bt.id
			where (b.time_expire >= '.Db::quote(i()->Time()).' or time_expire is null)');
		$identityBans = Arrays::compileSubsOnKey($rows,'identity');
		foreach($identityBans as $identity => $bans){
			$putBans = array();
			foreach($bans as $ban){
				$putBans[] = Arrays::extract(array('reason','time_expire'),$ban);
			}
			Cache::set('bans-'.$identity,serialize($putBans));
		}
	}
	///presents ban message, or clears ban if all expired
	private function banned($bans){
		foreach($bans as $k=>$ban){
			if($ban['time_expire'] == -1 || $ban['time_expire'] > i()->Time()->unix()){
				die('Banned.  Reason: '.$ban['reason'].'; Until: '.($ban['time_expire'] == -1 ? 'Indefinite' : i()->Time($ban['time_expire']).' UTC'));
			}
		}
		//died on no ban, so all of them expired.  So, clear ban cache.
		Cache::delete('bans-'.$this->identity);
	}
	///get banning info for identity
	private function getBanning(){
		return (array)unserialize(Cache::get('banning-'.$this->identity));
	}
	///set banning info ofr identity
	private function setBanning($data){
		Cache::set('banning-'.$this->identity,serialize($data),0);
	}
	
	///Adds points to a ban type, and, upon limit, bans
	/**
	Upon reaching a limit, ban is applied, and, name+, if present, is incremented.
	Ban time, if present, is passed to new Time().  Otherwise, permanent.
	
	@param	name	ban type name
	@param	points	points to add to current
	*/
	private function points($name,$points=1,$exitOnBan=true){
		$banType = self::$banTypes[$name];
		if(!$banType){
			return;
		}
		$banning = $this->getBanning();
		//append the ban type with the new instance
		$banning[$name][] = array(time(),$points);
		
		//+	check if over limit {
		$expired = time() - $banType['time_span'];
		foreach($banning[$name] as $k=>$instance){
			if($instance[0] < $expired){
				unset($banning[$name][$k]);
			}else{
				$total += $instance[1];
			}
		}
		//+	}
		if($total >= $banType['limit']){
			unset($banning[$name]);//ban is  being set, no reason to keep the banning  info (and might cause wrongful reban)
			$this->setBanning($banning);
			$this->points($name.'+',1,false);
			$this->ban($name,$exitOnBan);
		}
		$this->setBanning($banning);
	}
	///add a ban
	/**
	@param	name	name of ban_type
	@param	exit	whether to exit after adding ban
	*/
	private function ban($name,$exit=true){
		$banType = self::$banTypes[$name];
		Db::insert('ban',array(
				'identity' => $this->identity,
				'type_id_' => $banType['id'],
				'time_expire' => ($banType['ban_time'] ? i()->Time($banType['ban_time']) : null),
				'time_created' => i()->Time()
			));
		$bans = unserialize(Cache::get('bans-'.$this->identity));
		$bans[] = array('reason' =>$banType['reason'],'time_expire'=>i()->Time($banType['ban_time'])->unix());
		Cache::set('bans-'.$this->identity,serialize($bans));
		if($exit){
			$this->banned($bans);
		}
	}
}