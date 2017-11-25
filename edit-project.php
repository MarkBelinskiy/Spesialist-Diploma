<?php
if (!isset($_COOKIE['auth'])) {
  header('Location: index.php');
  die();
}
require_once 'header.php'; 

$login = $_COOKIE['auth'];
$user_projects = get_user_project_list($login, $pdo);
?>

<?php require_once 'navigation.php'; ?>

<main role="main" class="container" id="editProject">
  <?php if (count($user_projects)) :?>
    <div class="starter-template">
      <h1>Update projects</h1>
      <p class="lead">Press "Update" to change name or description of some of your project</p>
    </div> 
    <div class="row">
      <?php foreach ($user_projects as $project) :?>
        <div class="project-block text-center col-md-6">
          <?php if ($project['Name']) :?>
            <div class="name"><h2><?php echo $project['Name']; ?></h2></div>
          <?php endif; ?>
          <?php if ($project['Description']) :?>
            <div class="description">
              <p><?php echo $project['Description']; ?></p>
            </div>
          <?php endif; ?>
          <button data-id="<?php echo $project['ID_project'] ?>" class="btn btn-outline-warning my-2 my-sm-0 edit">
            Update work
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <div class="starter-template">
      <h1>You don't have any projects</h1>
      <p class="lead">Create your first project, to start work</p>
    </div>
  <?php endif; ?>

</main><!-- /.container -->
<div class="modal fade" id="updateModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Project</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <form class="form-update" id="update-form">
        <input type="hidden" name="project_id">
        <label for="name">Project name</label>
        <input type="text" id="name" name="project_name" class="form-control" autofocus>
        <div class="form-group">
          <label for="project_desc">Project description</label>
          <textarea name="project_desc" class="form-control" id="project_desc" rows="3"></textarea>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Update</button>
        <span class="msg"></span>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
<?php require_once 'footer.php';