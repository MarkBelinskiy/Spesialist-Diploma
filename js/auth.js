$(document).ready(function () {
	$('form#registration').on('submit', function(event) {
		event.preventDefault();
		var data = $(this).serializeArray();
		var msg = $('form#registration span.msg');
		msg.removeClass().addClass('msg').text('');
		console.log(data);
		$.ajax({
			type: "POST",
			url: 'add_user.php',
			data: {
				data : data
			},
			error: function(error){
				console.log(error);
			},
			success: function(data){
				console.log(data);

				if (data.status == 'error') {
					msg.addClass('error').text(data.message);
				} else if (data.status == 'success') {
					msg.addClass('success').text(data.message);
				} else if (data.status == 'redirect') {
					window.location.href = data.message;
				}
			}
		});
	});


	$('form#sign-in').on('submit', function(event) {
		event.preventDefault();
		var data = $(this).serializeArray();
		console.log(data);
		var msg = $('form#sign-in span.msg');
		msg.removeClass().addClass('msg').text('');
		$.ajax({
			type: "POST",
			url: 'auth_user.php',
			data: {
				data : data
			},
			error: function(error){
				console.log(error);
			},
			success: function(data){
				console.log(data);
				
				if (data.status == 'error') {
					msg.addClass('error').text(data.message);
				} else if (data.status == 'success') {
					msg.addClass('success').text(data.message);
				} else if (data.status == 'redirect') {

					window.location.href = data.message;
				}
			}
		});
	});


	$('form#create-form').on('submit', function(event) {
		event.preventDefault();
		var data = $(this).serializeArray();
		console.log(data);
		var msg = $('form#create-form span.msg');
		msg.removeClass().addClass('msg').text('');
		$.ajax({
			type: "POST",
			url: 'create_project.php',
			data: {
				data : data
			},
			error: function(error){
				console.log(error);
			},
			success: function(data){
				console.log(data);
				
				if (data.status == 'error') {
					msg.addClass('error').text(data.message);
				} else if (data.status == 'success') {
					msg.addClass('success').text(data.message);
				} else if (data.status == 'redirect') {
					window.location.href = data.message;
				}
			}
		});
	});
});