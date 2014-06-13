<? 
$fields = $page->tool->gets(array('show','title','input','note'),$page->concern ? $page->concern : CRUDPage::UPDATE);
$form = new Form($page->tool->formatItem(null,array_keys($fields['show'])));
?>
<form action="" method="post">
	<input type="hidden" name="_cmd_update" value="1"/>
	<table class="standard">
		<tbody>
<?	foreach($fields['show'] as $k=>$v){?>
<?		if(is_a($v,'closure')){//in the case the show is a function, allow it full control at this point?>
			<?=$v($page->tool->formatItem[$k],$page->item)?>
<?		}else{
			if(is_a($fields['input'][$k],'closure')){//in the case the input is a function, use that instead of Form::method
				$input = $fields['input'][$k]($page->tool->formatItem[$k],$page->item);
				$formMethod = '';
			}else{
				$formMethod = $fields['input'][$k]['form'];
				$input = call_user_func_array(array($form,$formMethod),(array)$fields['input'][$k]['options']);
			}
			if($formMethod == 'hidden'){
?>
			<?=$input?>
<?			}else{?>
			<tr>
				<?=FormStructure::fieldColumns($k,$fields['title'][$k],$input,$fields['note'][$k])?>
			</tr>
<?			}?>
<?		}?>
<?	}?>
		</tbody>
		<tfoot>
			<tr class="submitRow">
				<td colspan="2" class="formAction"><?=Form::submit('Update')?></td>
			</tr>
		</tfoot>
	</table>
</form>