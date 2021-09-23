<?php 



require_once ('config.php');

require_once (ROOT_DIR.'\helpers\Vaildators.php');
require_once (ROOT_DIR.'\beens\Users.php');
require_once (ROOT_DIR.'\controllers\UserControler.php');
require_once (ROOT_DIR.'\layouts\header.php');
$errors = [];
$registrationProcessStarted = false;
if (isset($_REQUEST['name'])  ){
  $registrationProcessStarted= true;
  $validator = new Vaildator ();
  $errors= ( $validator -> vaildateRegistration($_REQUEST));
  if ( count($errors) == 0) {
    $_REQUEST = $validator -> sentisizeUserInputRegistration($_REQUEST) ;
  }
} 

?>
  <!-- Page Content -->

  <section class="register">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1>Register Now</h1>
          <br>
        <?php
          if ( $registrationProcessStarted && count($errors) == 0 ) {
            $newUser = new User (null , $_REQUEST['name'] , $_REQUEST['username'] , $_REQUEST['password'], $_REQUEST['email'] , $_REQUEST['phone']);
            $usercontroler = new UserController();
            $newUser->pofileImage = $usercontroler->getUploadedImage($_FILES) ; 
            $result = $usercontroler -> addNewUser ( $newUser);
            if (  isset ($result['error'] )){
              $errors = $result ;
            }else if (isset ($result['success']) ) {
              echo '<div class="alert alert-success" role="alert">' ." Congrats you have been registared sucessfully". "</div>" ;
            }
          }
        ?>
        <?php 
        foreach ( $errors as $error) {
          echo ' <div class="alert alert-danger" role="alert">' . $error . "</div>" ;
        }
        ?>
          <form id="Register_form" name="posts_from" method="POST" action="register.php"  enctype="multipart/form-data" > 
          <div class="row">
            <label class="col-md-4">Name:</label><input name="name" class="col-md-8 form-control" required />
          </div>
          <div class="row">
            <label class="col-md-4">Username:</label> <input name="username" class="col-md-8 form-control" required/>
          </div>
          <div class="row">
            <label class="col-md-4">Password:</label> <input name="password" type="password" class="col-md-8 form-control" required/>
          </div>
          <div class="row">
            <label class="col-md-4">Confirm Password:</label> <input name="confirm_password" type="password" class="col-md-8 form-control" required/>
          </div>
          <div class="row">
            <label class="col-md-4">E-mail:</label> <input name="email" type="email" class="col-md-8 form-control" required />
          </div>
          <div class="row">
            <label class="col-md-4">Phone:</label> <input name="phone" class="col-md-8 form-control" />
          </div>
          <div class="row">
            <label class="col-md-4">Profile Image:</label> <input  type="file" name="Profile-image" class="col-md-8 " required  />
          </div>
          <button class="btn btn-success btn-block">Register</button>
          </form>
        </div>
      </div>
    </div>
  </section>


  <?php 
    include 'layouts/footer.php';
    ?>