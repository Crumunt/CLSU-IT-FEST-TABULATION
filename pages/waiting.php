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
   max-width: 500px;
   margin-bottom: 30px;
   animation: fadeIn 1.5s ease-in-out;
   transition: transform 0.3s ease, filter 0.3s ease;
   /* Added transition for smooth hover effect */
  }

  @keyframes fadeIn {
   0% {
    opacity: 0;
   }

   100% {
    opacity: 1;
   }
  }

  .logo:hover {
   transform: scale(1.1);
   filter: brightness(1.2);
  }

  .btn-custom {
   background: #4CAF50;
   color: white;
   font-size: 1.2em;
   font-weight: 600;
   padding: 12px 30px;
   border-radius: 30px;
   border: none;
   text-transform: uppercase;
   letter-spacing: 1px;
   cursor: pointer;
   transition: transform 0.3s ease, color 0.3s ease, background-color 0.3s ease;
  }

  .btn-custom:hover {
   transform: translateY(-5px);
   background: #45a049;
   color: white;
  }

  .btn-custom:focus {
   outline: none;
  }
 </style>
</head>

<body>

 <a href="final.php">
  <img src="../assets/logo1.png" alt="Logo" class="logo">
 </a>

 <!-- <a href="final.php"><button class="btn btn-custom">REVEAL FINALISTS</button></a> -->

 <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>