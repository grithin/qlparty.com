$(function(){
	$('a.delete').each(function(){
		var location = $(this).attr('href');
		$(this).attr('href','#');
		$(this).attr('data-location',location);
	}).click(function(e){
		e.preventDefault();
		if(confirm('Are you sure you want to delete this?')){
			tp.relocate($(this).attr('data-location'));
		}
	})
	//+	update elements to be time correct
	$('.timeAgo').each(function(){
			$(this).text(tp.date.timeAgo($(this).text())+' ago')
		});
	tp.ui.timezoneOffset = new Date().getTimezoneOffset()
	$('.unixtime').each(function(){
			var unixtime = $(this).text()
			var time = tp.date.format('Y-m-d H:i',unixtime)
			$(this).text(time)
		});
	//+	}
	function voteOn(){
		if($(this).hasClass('voteUp')){
			var direction = 1;
		}else{
			var direction = -1;
		}
		var id = $(this).parents('*[data-id]:first').attr('data-id')
		var url = $(this).parents('*[data-voteUrl]:first').attr('data-voteUrl')
		var ele = $(this);
		ele.unbind('click').css('cursor','default')
		$.get(url,{direction:direction,id:id,_cmd_create:1},function(json){
				ele.text(Number(ele.text()) + 1)
			},'json')
	}
	$('.voteUp,.voteDown').click(voteOn);
	$('.matcher').change(function(){
		var matcher = $(this)
		var matcheeType = matcher.attr('matchee-type')
		if(matcher.attr('type') == 'checkbox'){
			var value = matcher.prop('checked');
			$('.matchee[matchee-type="'+matcheeType+'"]').prop('checked',value)
		}else{
			$('.matchee[matchee-type="'+matcheeType+'"]').val(matcher.val())
		}
	})
	
	tp.ui.userMessages = function(){
			$.get('/messaging/check',null,function(json){
					if(typeof(json)=='object'){
						tp.ui.insertMessages(json.messages)
						$('#messageCount').text('('+json.count+')')
					}else{
						$('#messageCount').text('('+json+')')
					}
					setTimeout('tp.ui.userMessages()',120000)
				},'json')
		}
	tp.ui.userMessages();
	
});