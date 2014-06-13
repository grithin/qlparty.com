<? 
$fields = $page->tool->gets(array('show','title','format','note'),CRUDPage::READ);
$item = $page->tool->formatItem();
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
<?	if(1|| $page->item['actor_id'] != User::actorId()){?>
<a class="button" href="<?=$base.'../email?id='.$page->item['id']?>">Email Volunteer</a>
<?	}?>

<table class="standard">
<?	foreach($fields['show'] as $k=>$v){?>
	<tr>
		<td><?=$fields['title'][$k]?></td>
		<td><?=$item[$k]?></td>
	</tr>
<?	}?>
</table>