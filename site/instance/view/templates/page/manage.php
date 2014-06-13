<? 
/**
Can configure with array: $page->templateOptions, using:
	create = false
	delete = false
	description = text
*/
$fields = $page->tool->gets(array('show','title','format'),$page->concern ? $page->concern : CRUDPage::MANAGE);
?>
<style type="text/css">
	.button, input[type=submit]{
		font-size:8pt;
	}
	.submitRow{
		text-align:right;
	}
	.submitRow td{
		padding-right:20px;
	}
</style>
<?=$page->templateOptions['description']?>

<?	if($page->templateOptions['create'] !== false){?>
<a class="button" href="/volunteer/create">Create</a>
<?	}?>
<form action="" method="post">
	<input type="hidden" name="_cmd_delete" value="1"/>
<table class="standard sortContainer" data-paging="<?=$page->paging['pages'].','.$page->paging['page']?>" data-sort="<?=$page->sort?>">
	<thead>
		<tr>
<?	foreach($fields['show'] as $k=>$v){?>
			<th data-field="<?=$k?>"><?=$fields['title'][$k]?></th>
<?	}?>
<?	if($page->templateOptions['delete'] !== false){?>
			<th>
				Delete
				<?=Form::checkbox('matcher1',null,array('class'=>'matcher','@matchee-type'=>'item'))?>
			</th>
<?	}?>
		</tr>
	</thead>
	<tbody>
<?	foreach($page->items as $item){?>
		<tr>
<?		foreach($fields['show'] as $k=>$v){?>
<?			if(is_a($v,'closure')){?>
			<?=$v($page->tool->format($fields['format'][$k],$item[$k],$item),$page->item)?>
<?			}else{?>
			<td><?=$page->tool->format($fields['format'][$k],$item[$k],$item)?></td>
<?			}?>
<?		}?>
<?		if($page->templateOptions['delete'] !== false){?>
			<td><?=Form::checkbox('delete['.$item['id'].']',null,array('class'=>'matchee','@matchee-type'=>'item'),$item['id'])?></td>
<?		}?>
		</tr>
<?	}?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="<?=count($fields['show']) + 1?>" class="paging"></td>
		</tr>
<?		if($page->templateOptions['delete'] !== false){?>
		<tr class="submitRow">
			<td colspan="<?=count($fields['show']) + 1?>" class="formAction"><?=Form::submit('Delete')?></td>
		</tr>
<?		}?>
	</tfoot>
</table>
	
</form>