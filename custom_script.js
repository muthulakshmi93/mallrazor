$(document).ready(function(){
	$('#update_name').click(function(){
		var user_name = $('#usr').val();
		
		
		$.ajax({
					url: "update_user.php",
					type: "post",
					data: {user_name: user_name},
					success: function (response) {	
						$('#uname').html(user_name);
						$('#editUname').removeClass('show');
						$('.modal-backdrop').removeClass('show');
					},
					error: function(jqXHR, textStatus, errorThrown) {
					}
		});	
	
	});

});