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

<main role="main" class="container" id="deleteProject">
  <?php if (count($user_projects)) :?>
    <div class="starter-template">
      <h1>Delete projects</h1>
      <p class="lead">Press "Remove work" to delete some of your project</p>
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
          <button data-id="<?php echo $project['ID_project'] ?>" class="btn btn-outline-danger my-2 my-sm-0 delete">
            Remove work
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

<?php require_once 'footer.php';