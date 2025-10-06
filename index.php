<?php
session_start();
$host = 'localhost';
$dbname = 'tabulation';
$username = 'root';
$password = '';
$port = '3306';     

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['judge_id'])) {
 $_SESSION['judge_id'] = $_POST['judge_id'];
 header("Location: pages/production.php"); // Redirect to production.php after login
 exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Judge Login</title>
 <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
 <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<style>
 body,
 html {
  height: 100%;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  text-align: center;
  background: linear-gradient(to bottom, #FFF0A5, #468966);
  font-family: 'Roboto', sans-serif;
 }

 .logo {
  max-width: 400px;
  margin-bottom: 30px;
  animation: fadeIn 1.5s ease-in-out;
 }

 @keyframes fadeIn {
  0% {
   opacity: 0;
  }

  100% {
   opacity: 1;
  }
 }

 .form-container {
  background: rgba(52, 49, 49, 0.5);
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
 }

 .form-container .form-label {
  font-weight: 500;
 }

 .btn-custom {
  background-color: #468966;
  border-color: #468966;
  width: 100px;
  color: white;
 }

 .btn-custom:hover {
  background-color: #FFF0A5;
  border-color: #FFF0A5;
  color: black;
 }

 .btn-primary {
  width: 100%;
  padding: 12px;
  font-size: 1.2em;
 }
</style>

<body>

 <img src="assets/logo1.png" alt="Logo" class="logo">

 <div class="form-container">
  <form method="post">
   <div class="mb-3">
    <label for="judge_id" class="form-label text-white">Enter Judge ID:</label>
    <input type="number" class="form-control" name="judge_id" id="judge_id" required>
   </div>
   <button type="submit" class="btn btn-custom">ENTER</button>
  </form>
 </div>

</body>

<script type="module" src="bootstrap/js/bootstrap.bundle.min.js"></script>

</html>