<?php 
  session_start();
  $_SESSION['query'] = null
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

</head>
<body class="bg-light">

<div class="container">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4">Administrator</h1>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="pos">POS</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="orders">Orders</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="ordersQueue">Orders Queue</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="inventory">Inventory</button>
    <button class="btn btn-lg btn-primary col-12 mb-3" id="salesReport">Sales Report</button>
    <button class="btn btn-lg btn-danger col-12 mb-3" id="Logout">Logout</button>

    <script>
    document.getElementById("pos").onclick = function () {window.location.replace('adminPos.php'); };
    document.getElementById("orders").onclick = function () {window.location.replace('adminOrdersList.php'); };
    document.getElementById("orders").onclick = function () {window.location.replace('adminOrdersList.php'); };
    document.getElementById("ordersQueue").onclick = function () {window.location.replace('adminOrdersQueue.php'); };
    document.getElementById("inventory").onclick = function () {window.location.replace('adminInventory.php'); };
    document.getElementById("salesReport").onclick = function () {window.location.replace('adminSalesReport.php'); };
    document.getElementById("Logout").onclick = function () {window.location.replace('Login.php'); 
    $.post(
        "method/clearSessionMethod.php", {
        }
    );}
    </script>

  </div>
</div>
  
</body>
</html>