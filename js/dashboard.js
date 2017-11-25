$(document).ready(function () {

	$('#deleteProject').on('click', '.delete', function(event) {
		event.preventDefault();
		var current = $(this);
		var projectToDelete = current.data('id');
		if (confirm('Are you sure you want to delete project?')) {
			$.ajax({
				type: "POST",
				url: 'delete_project.php',
				data: {
					data : projectToDelete
				},
				error: function(error){
					console.log(error);
				},
				success: function(data){
					if (data.status == 'error') {
						console.log(data.status);
					} else if (data.status == 'success') {
						$(current).closest('.project-block').fadeOut(300, function() { $(this).remove(); });
					} 
				}
			});
		}
	});


	$('#editProject').on('click', '.edit', function(event) {
		event.preventDefault();
		var current = $(this);
		var projectToEdit = current.data('id');
		console.log(projectToEdit);
		var modal = $('#updateModal');

		$.ajax({
			type: "POST",
			url: 'edit_project.php',
			data: {
				get_project_id : projectToEdit
			},
			error: function(error){
				console.log(error);
			},
			success: function(data){
				console.log(data);

				$('#updateModal input[name="project_id"]').val(projectToEdit);
				$('#updateModal input[name="project_name"]').val(data.Name);
				$('#updateModal textarea[name="project_desc"]').val(data.Description);
				modal.modal('show');
			}
		});
	});


	$('#update-form').on('submit', function(event) {
		event.preventDefault();
		var data = $(this).serializeArray();
		var modal = $('#updateModal');
		var msg = $('#updateModal span.msg');
		msg.removeClass().addClass('msg').text('');

		$.ajax({
			type: "POST",
			url: 'edit_project.php',
			data: {
				data : data
			},
			error: function(error){
				console.log(error);
			},
			success: function(data){
				if (data.status == 'error') {
					msg.addClass('error').text(data.message);
				} else {
					var project_id = data.project_id;
					var project_name = data.project_name;
					var project_description = data.project_description;
					var projectBlock = $('button[data-id="'+project_id+'"]');
					projectBlock.siblings('.name').children('h2').text(project_name);
					projectBlock.siblings('.description').children('p').text(project_description);
					modal.modal('hide');
				}
			}
		});
	});

});