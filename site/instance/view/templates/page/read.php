<? 
$fields = $page->tool->gets(array('show','title','note'),$page->concern ? $page->concern : CRUDPage::READ);
$item = $page->tool->formatItem(null,array_keys($fields['show']));
$base = RequestHandler::currentPath();
?>

<style type="text/css">
	.button{
		font-size:8pt;
	}
</style>
<?	if($page->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){?>
<a class="button" href="<?=$base.'../update?id='.$page->item['id']?>">Update</a>
<?	}?>

<table class="standard">
<?	foreach($fields['show'] as $k=>$v){?>
<?		if(is_a($v,'closure')){//in the case the show is a function, allow it full control at this point?>
			<?=$v($page->tool->formatItem[$k],$page->item)?>
<?		}else{?>
	<tr>
		<td><?=$fields['title'][$k]?></td>
		<td><?=$item[$k]?></td>
	</tr>
<?		}?>
<?	}?>
</table>