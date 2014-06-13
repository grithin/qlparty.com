$('*[name="Sign Up"]').click(function(e){
	e.stopPropagation()
	$('*[name="_cmd_update"]').remove()
	$('login').attr('action','signup')
	$('login').submit()
})