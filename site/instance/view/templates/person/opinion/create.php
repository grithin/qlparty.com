<form action="" method="post">
	<input type="hidden" name="_cmd_create" value="1"/>
	<table class="standard">
		<tbody>
			<tr>
				<td colspan="2">
					<h3><a href="/person/read/<?=$page->person['id']?>"><?=$page->person['name_first']?> <?=$page->person['name_middle']?> <?=$page->person['name_last']?></a></h3>
				</td>
			</tr>
			<tr>
				<?=FormStructure::text('Title','title',null,array('class'=>'long'))?>
			</tr>
			<tr>
				<?=FormStructure::textarea('Opinion','text',null,array('class'=>'big'))?>
			</tr>
		</tbody>
		<tfoot>
			<tr class="submitRow">
				<td colspan="2" class="formAction"><?=Form::submit('Create')?></td>
			</tr>
		</tfoot>
	</table>
</form>