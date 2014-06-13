<? 
$fields = $page->tool->gets(array('show','title','note'),$page->concern ? $page->concern : CRUDPage::READ);
$item = $page->tool->formatItem(null,array_keys($fields['show']));
$base = RequestHandler::currentPath();
?>

<style type="text/css">
	.button{
		font-size:8pt;
	}
	.thickOutline{
		border:10px solid #FFDCAE;
		border-radius:10px 10px 10px 10px;
		padding:10px;
		margin-bottom:7px;
	}
	.joiner{
		float:left;
		padding-right: 8px;
	}
	.joiner:after{
		content: ", ";
	}
	.joiner:last-child:after {
		content: "";
	}
</style>
<?	if($page->item['actor_id'] == User::actorId() || User::hasPrivilege('admin')){?>
<a class="button" href="<?=$base.'../update?id='.$page->item['id']?>">Update</a>
<?	}?>
<?	if($page->item['has_joined']){?>
<a class="button" href="<?=$base.'?id='.$page->item['id']?>&unjoin=1">Unjoin</a>
<?	}else{?>
<a class="button" href="<?=$base.'?id='.$page->item['id']?>&join=1">Join</a>
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

<?	if($page->item['joiners']){?>
<section>
	<h3>Joiners</h3>
	<main class="thickOutline">
<?		foreach($page->item['joiners'] as $joiner){?>
		<div class="joiner">
			<?=ViewTool::actor($joiner['display_name'],$joiner['actor_id'])?>
		</div>
<?		}?>
	</main>
<?	}?>