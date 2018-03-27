<?php
session_start();
if ( isset($_POST['cancel'])) {
  header("Location: index.php");
  return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; //pw: php123

//$failure = false;

if ( isset($_POST['who']) && isset($_POST['pass']) ) {
  unset($_SESSION['account']);
  if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) <1) {
    $_SESSION['failure'] = "Email and password are required";
    header('Location: login.php');
    return;
  } else if ( strpos($_POST['who'], '@') === false) {
    $_SESSION['failure'] = "Email must have an at-sign (@)";
    header('Location: login.php');
    return;
  } else {
    $check = hash('md5', $salt.$_POST['pass']);
    if ( $check == $stored_hash ) {
      error_log("Login success ".$_POST['who']);
      $_SESSION['account'] = $_POST['who'];
      header("Location: index.php");
      return;
    } else {
      $_SESSION['failure'] = "Incorrect password";
      error_log("Login fail ".$_POST['who']." $check");
      header('Location: login.php');
      return;
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login page</title>
</head>
<body>
  <h1>Please log in</h1>
  <?php
  if ( isset($_SESSION['failure'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
  }
  ?>
  <form method="POST">
    <label for="nam">Email</label>
    <input type="text" name="who" id="nam"><br/>
    <label for="pwd">Password</label>
    <input type="password" name="pass" id="pwd"><br/>
    <input type="submit" value="Log in">
    <input type="submit" name="cancel" value="Cancel">
  </form>
</body>
</html>
