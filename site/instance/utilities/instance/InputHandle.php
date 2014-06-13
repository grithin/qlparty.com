<?
class InputHandle extends FieldIn{
	static $types = array(
			'basicText' => array('f:trim','f:conditionalNl2Br',array('f:stripTags','br,a,b,i,u,ul,li,ol,p','href'),'f:trim','v:filled','v:htmlTagContextIntegrity')
		);
}


