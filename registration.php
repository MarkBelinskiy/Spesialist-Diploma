<?php 
if (!empty(isset($_COOKIE['auth']))) {
  if (isset($_GET['auth']) && $_GET['auth']  == 'logout') {
    setcookie("auth", "", time() - 3600);
  } else {
    header('Location: dashboard.php');
  }
}
require_once 'header.php'; ?>

<div class="container">

  <form class="form-signin" id="registration">
    <h2 class="form-signin-heading">Registration</h2>
    <label for="inputLogin" class="sr-only">Login</label>
    <input type="text" id="inputLogin" class="form-control" name="login" placeholder="Login" autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password">
    <label for="inputConPassword" class="sr-only">Confirm Password</label>
    <input type="password" id="inputConPassword" name="password2" class="form-control" placeholder="Confirm Password">
    <div class="checkbox">
      <a href="/index.php">Login</a>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Register me</button>
    <span class="msg"></span>
  </form>

</div> <!-- /container -->
<?php require_once 'footer.php'; ?>
