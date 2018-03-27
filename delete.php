<?php
require_once "pdo.php";
session_start();

if (isset($_POST['cancel'])){
  header('Location: index.php');
  return;
}

if (isset($_POST['delete']) && isset($_POST['cars_id'])) {
  $sql = "DELETE FROM cars WHERE cars_id = :aut";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':aut' => $_POST['cars_id']));
  $_SESSION['success'] = 'Record deleted';
  header('Location: index.php');
  return;
}

if (!isset($_GET['cars_id'])){
  $_SESSION['error'] = 'Missing cars_id';
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT make, cars_id FROM cars WHERE cars_id = :val");
$stmt->execute(array(':val' => $_GET['cars_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
  $_SESSION['error'] = 'Bad value for cars_id';
  header('Location: index.php');
  return;
}
 ?>

 <p>Confirm: Deleting <?= htmlentities($row['make']) ?></p>

 <form method="post">
   <input type="hidden" name="cars_id" value="<?= $row['cars_id'] ?>">
   <input type="submit" value="Delete" name="delete">
   <input type="submit" name='cancel' value="Cancel">
 </form>
