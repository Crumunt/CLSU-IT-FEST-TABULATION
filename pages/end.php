<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>CLSU Tabulation System</title>
 <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

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

  @keyframes fadeIn {
   0% {
    opacity: 0;
   }

   100% {
    opacity: 1;
   }
  }

  .thank-you-text {
   font-size: 3em;
   font-weight: 800;
   color: #fff;
   text-transform: uppercase;
   letter-spacing: 4px;
   font-family: 'Poppins', sans-serif;
   text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5);
   padding: 25px 50px;
   border-radius: 12px;
   width: auto;
   max-width: 100%;
   text-align: center;
   box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
   transition: transform 0.3s ease, color 0.3s ease;
   animation: fadeIn 2s ease-in-out;

  }

  .thank-you-text:hover {
   transform: translateY(-5px);
   color: white;
  }
 </style>
</head>

<body>
 <h3 class="thank-you-text">THANK YOU JUDGES</h3>
 <img src="../assets/logo1.png" alt="Logo" class="logo">


 <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>