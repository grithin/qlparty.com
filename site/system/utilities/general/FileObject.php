<?
///purely to patch the failure of splFileObject to handle a while loop
class FileObject extends SplFileObject{
	function fgets(){
		if(!$this->valid()){
			return false;
		}
		return parent::fgets();
	}
}