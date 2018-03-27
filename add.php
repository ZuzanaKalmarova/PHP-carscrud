<?php
session_start();
if ( !isset($_SESSION['account'])){
  die('ACCESS DENIED');
}

if (isset($_POST['cancel'])){
  header('Location: index.php');
  return;
}

require_once "pdo.php";

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])
&& isset($_POST['model'])) {
  if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    $_SESSION['error'] = 'Mileage and year must be numeric';
    header('Location: add.php');
    return;
  }
  if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
    $_SESSION['error'] = 'All fields are required';
    header('Location: add.php');
    return;
  }
  $stmt = $pdo->prepare('INSERT INTO cars
    (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)');
  $stmt->execute(array(
    ':mk' => $_POST['make'],
    ':md' => $_POST['model'],
    ':yr' => $_POST['year'],
    ':mi' => $_POST['mileage'])
  );
  $_SESSION['success'] = 'Record inserted';
  header('Location: index.php');
  return;

}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
   <title>Zuzana add auto</title>
 </head>
 <body>
   <h1>Add new car</h1>
   <?php
   if (isset($_SESSION['error'])) {
     echo ('<p style="color:red;">'.htmlentities($_SESSION['error'])."</p>\n");
     unset($_SESSION['error']);
   }
    ?>
  <form method="POST">
    <label for="make">Make:</label>
    <input type="text" name="make" id="make"><br/>
    <label for="model">Model:</label>
    <input type="text" name="model" id="model"><br/>
    <label for="year">Year:</label>
    <input type="text" name="year" id="year"></br>
    <label for="mileage">Mileage:</label>
    <input type="text" name="mileage" id="mileage"></br>
    <input type="submit" value="Add">
    <input type="submit" name='cancel' value="Cancel">
  </form>
</body>
</html>
