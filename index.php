<?php 
if (!empty(isset($_COOKIE['auth']))) {
  if (isset($_GET['auth']) && $_GET['auth']  == 'logout') {
    setcookie("auth", "", time() - 3600);
  } else {
    header('Location: /dashboard.php');
  }
}
$success_reg = (isset($_GET['registration']) && $_GET['registration'] == 'success') ? 'Registration success, you can use login and pass' : false;

require_once 'header.php'; 
?>

<div class="container">
  <?php if ($success_reg) : ?>
    <div class="success-registration"><?php echo $success_reg; ?></div>
  <?php endif; ?>
  <form class="form-signin" id="sign-in">
    <h2 class="form-signin-heading">Please sign in</h2>
    <label for="inputLogin" class="sr-only">Login</label>
    <input type="text" id="inputLogin" name="login" class="form-control" placeholder="Login" autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password">
    <div class="checkbox">
      <a href="/registration.php">Registration</a>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <span class="msg"></span>
  </form>

</div> <!-- /container -->
<?php require_once 'footer.php';
