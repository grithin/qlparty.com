<form action="" method="post">
	<input type="hidden" name="_cmd_create" value="1"/>
	<table class="standard">
		<tbody>
			<tr>
				<td colspan="2">
					<a href="/person/read/<?=$page->person['id']?>"><?=$page->person['name_first']?> <?=$page->person['name_middle']?> <?=$page->person['name_last']?></a>
				</td>
			</tr>
			<tr>
				<?=FormStructure::select('Attribute Type','type_id',$page->attributeTypes)?>
			</tr>
			<tr>
				<?=FormStructure::text('Value','value',null,array('class'=>'long'))?>
			</tr>
		</tbody>
		<tfoot>
			<tr class="submitRow">
				<td colspan="2" class="formAction"><?=Form::submit('Add')?></td>
			</tr>
		</tfoot>
	</table>
</form>