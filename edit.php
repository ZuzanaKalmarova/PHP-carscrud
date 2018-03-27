<?php
require_once "pdo.php";
session_start();

if ( !isset($_SESSION['account'])){
  die('ACCESS DENIED');
}

if (isset($_POST['cancel'])){
  header('Location: index.php');
  return;
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])
&& isset($_POST['model'])) {

  if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    $_SESSION['error'] = 'Mileage and year must be numeric';
    header('Location: edit.php?cars_id='.$_POST['cars_id']);
    return;
  }

  if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
    $_SESSION['error'] = 'All fields are required';
    header('Location: edit.php?cars_id='.$_POST['cars_id']);
    return;
  }

  $sql = "UPDATE cars SET make = :make, model = :model,
         year = :year, mileage = :mileage
         WHERE cars_id = :cars_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':make' => $_POST['make'],
    ':model' => $_POST['model'],
    ':year' => $_POST['year'],
    ':mileage' => $_POST['mileage'],
    ':cars_id' => $_POST['cars_id']));
  $_SESSION['success'] = 'Record updated';
  header('Location: index.php');
  return;
}

if (!isset($_GET['cars_id'])){
  $_SESSION['error'] = 'Missing cars_id';
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM cars WHERE cars_id = :val");
$stmt->execute(array(':val' => $_GET['cars_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
  $_SESSION['error'] = 'Bad value for cars_id';
  header('Location: index.php');
  return;
}

if (isset($_SESSION['error'])){
  echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
  unset($_SESSION['error']);
}

$ma = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$ye = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$cars_id = $row['cars_id'];
 ?>

 <p>Edit record</p>
 <form method="post">
   <p>Make:
     <input type="text" name="make" value="<?= $ma ?>"></p>
   <p>Model:
     <input type="text" name="model" value="<?= $mo ?>"></p>
   <p>Year:
     <input type="text" name="year" value="<?= $ye ?>"></p>
   <p>Mileage:
     <input type="text" name="mileage" value="<?= $mi ?>"></p>
   <input type="hidden" name="cars_id" value="<?= $cars_id ?>">
   <input type="submit" value="Update">
   <input type="submit" name='cancel' value="Cancel">
   </form>
