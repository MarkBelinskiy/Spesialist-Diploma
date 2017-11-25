<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="dashboard.php">Hello, <?php echo $login; ?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarsExampleDefault">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item"><a class="nav-link" href="create-project.php">Create project</a></li>
			<li class="nav-item"><a class="nav-link" href="edit-project.php">Edit project</a></li>
			<li class="nav-item"><a class="nav-link" href="delete-project.php">Delete project</a></li>
		</ul>
		<form class="form-inline my-2 my-lg-0">
			<a href="index.php?auth=logout" class="btn btn-outline-success my-2 my-sm-0">LogOut</a>
		</form>
	</div>
</nav>