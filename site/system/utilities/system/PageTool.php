<?
///Fallback used in case page has no PageTool utility
if(class_exists('Model')){
	class PageTool extends Model{}
}else{
	class PageTool{
		static $model,$id;
	}
}