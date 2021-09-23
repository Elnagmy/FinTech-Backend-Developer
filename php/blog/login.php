<?php 
require_once ('config.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : null;
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
$usercontroler = new UserController();
$error = null;
$user =null;
if ( $username != null && $password != null) {
  $user = $usercontroler -> TryUserLogin ( $username , $password ); 
  if ( $user == null) {
    $error = " Login is not sucessfull please check your username and password" ;
  } else if ( $user=="Blocked") {
    $error = " Your account is blocked " ;
  }else{
    header('Location:' . BASE_URL . '/index.php');
    die();
  }
}
require_once 'layouts/header.php';
?>

  <!-- Page Content -->

  <section class="register">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1>Login</h1>
          <br>
          <?php if ($error != null) { ?>
            <div class="alert alert-danger" role="alert">
            <?= $error ?>
          </div>
          <?php } ?>
          <?php if ($error= null) { ?>
            <div class="alert alert-success" role="alert">
            <div> You have been loged in successfully you can visit the home page from here</div>
          </div>
          <?php } ?>
        
          <form id="login_form" name="posts_from" method="POST" action="login.php">
            <div>
              <div class="row">
                <label class="col-md-4">Username Or Email:</label> <input name="username" class="col-md-8 form-control"  required />
              </div>
              <div class="row">
                <label class="col-md-4">Password:</label> <input name="password" type="password" class="col-md-8 form-control" required />
              </div>
              
              <button class="btn btn-success btn-block">Login</button>
            </div>
          </form>
      </div>
    </div>
  </section>

  <?php 

    require_once 'layouts/footer.php';

  ?>