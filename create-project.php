<?php
if (!isset($_COOKIE['auth'])) {
  header('Location: index.php');
  die();
}
require_once 'header.php'; 

$login = $_COOKIE['auth'];
?>
<?php require_once 'navigation.php'; ?>

<main role="main" class="container">
  <div class="starter-template">
    <h1>Create project</h1>
    <form class="form-signin" id="create-form">
      <label for="name">Project name</label>
      <input type="text" id="name" name="project_name" class="form-control" autofocus>
      <div class="form-group">
        <label for="project_desc">Project description</label>
        <textarea name="project_desc" class="form-control" id="project_desc" rows="3"></textarea>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
      <span class="msg"></span>
    </form>
  </div>

</main><!-- /.container -->

<?php require_once 'footer.php';