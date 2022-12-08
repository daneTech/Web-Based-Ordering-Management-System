<?php 
  $page = 'admin';
  include('method/query.php');
  include('method/checkIfAccountLoggedIn.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <script type="text/javascript" src="js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3">
                    <a href="admin.php">Admin</a>
                </h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="#" id="pos">
                        <i class="bi bi-tag me-2"></i>
                        Point of Sales
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="orders">
                        <i class="bi bi-minecart me-2"></i>
                        Orders
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="ordersQueue">
                        <i class="bi bi-clock me-2"></i>
                        Orders Queue
                    </a>
                </li>
                <li class="mb-2 active">
                    <a href="#">
                        <i class="bi bi-box-seam me-2"></i>
                        Inventory
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="salesReport">
                        <i class="bi bi-bar-chart me-2"></i>
                        Sales Report
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="accountManagement">
                        <i class="bi bi-person-circle me-2"></i>
                        Account Management
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="customerFeedback">
                        <i class="bi bi-chat-square-text me-2"></i>
                        Customer Feedback
                    </a>
                </li>
                <li class="mb-1">
                    <a href="#" id="adminTopUp">
                        <i class="bi bi-cash-stack me-2"></i>
                        Top-Up
                    </a>
                </li>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout">
                            <i class="bi bi-power me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list"></i>
                        <span>Dashboard</span>
                    </button>
                </div>
            </nav>
            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">

                    <div class="table-responsive col-lg-12 mb-5">
                        <table class="table table-striped table-bordered col-lg-12" id="tbl">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">IMAGE</th>
                                    <th scope="col">DISH</th>
                                    <th scope="col">PRICE</th>
                                    <th scope="col">STOCK</th>
                                    <th scope="col">Last Modified By:</th>
                                    <th scope="col">
                                        <button id="addButton" type="button" class="btn btn-success"
                                            data-bs-toggle="modal" data-bs-target="#loginModal">
                                            <i class="bi bi-plus me-1"></i>
                                            ADD NEW DISH
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "select * from WEBOMS_menu_tb";
                                    $resultSet = getQuery($query);
                                    if($resultSet != null){
                                    foreach($resultSet as $row){?>
                                <tr>
                                    <!-- image -->
                                    <td>
                                        <?php $pic = $row['picName']; echo "<img src='dishesPic/$pic' style=width:150px; height:150px>";?>
                                    </td>
                                    <!-- dish -->
                                    <td><?php echo $row['dish'];?></td>
                                    <!-- price -->
                                    <td><?php echo '₱'.$row['price']; ?></td>
                                    <!-- stock -->
                                    <td><?php echo $row['stock']; ?></td>
                                    <!-- staff (in-charge) -->
                                    <td><?php echo strtoupper($row['lastModifiedBy']); ?></td>
                                    <!-- options -->
                                    <td>
                                        <a class="btn btn-danger"
                                            href="?idAndPicnameDelete=<?php echo $row['orderType']." ".$row['picName']; ?>">
                                            <i class="bi bi-trash me-1"></i>
                                            DELETE
                                        </a>
                                        <a class="btn btn-warning"
                                            href="adminInventoryUpdate.php?idAndPicnameUpdate=<?php echo $row['orderType'].",".$row['dish'].",".$row['price'].",".$row['picName'].",".$row['stock']; ?>">
                                            <i class="bi bi-arrow-repeat me-1"></i>
                                            UPDATE
                                        </a>
                                    </td>
                                </tr>
                                <?php } 
                                    }
			  	                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- add new dish modal -->
            <div class="modal fade" role="dialog" id="loginModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body ">
                            <form method="post" class="form-group" enctype="multipart/form-data">
                                <input type="text" class="form-control form-control-lg mb-3" name="dishes"
                                    placeholder="ENTER DISH NAME" required>
                                <input type="number" class="form-control form-control-lg mb-3" name="price" step="any"
                                    placeholder="ENTER PRICE" required>
                                <input type="number" class="form-control form-control-lg mb-3" name="stock"
                                    placeholder="ENTER NUMBER OF STOCK" required>
                                <input type="file" class="form-control form-control-lg mb-3" name="fileInput" required>
                                <button type="submit" class="btn btn-lg btn-success col-12" name="insert">
                                    <i class="bi bi-plus me-1"></i>
                                    INSERT
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php
  //delete 
  if (isset($_GET['idAndPicnameDelete'])){
    $arr = explode(' ',$_GET['idAndPicnameDelete']);
    $id = $arr[0];
    $pic = $arr[1];
    $query = "DELETE FROM WEBOMS_menu_tb WHERE orderType='$id' ";
    if(Query($query)){
      unlink("dishespic/"."$pic");
      echo "<script> window.location.replace('adminInventory.php');</script>";
    }
  }

  //insert
  if(isset($_POST['insert'])){
  $dishes = $_POST['dishes'];
  $price = $_POST['price'];
  $file = $_FILES['fileInput'];
  $stock = $_POST['stock'];
  $fileName = $_FILES['fileInput']['name'];
  $name = $_SESSION['name'];

  $fileTmpName = $_FILES['fileInput']['tmp_name'];
  $fileSize = $_FILES['fileInput']['size'];
  $fileError = $_FILES['fileInput']['error'];
  $fileType = $_FILES['fileInput']['type'];
  $fileExt = explode('.',$fileName);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('jpg','jpeg','png');
  if(in_array($fileActualExt,$allowed)){
      if($fileError === 0){
          if($fileSize < 10000000){
              $fileNameNew = uniqid('',true).".".$fileActualExt;
              $fileDestination = 'dishesPic/'.$fileNameNew;
              move_uploaded_file($fileTmpName,$fileDestination);         
              $query = "insert into WEBOMS_menu_tb(dish, price, picName, stock, lastModifiedBy) values('$dishes','$price','$fileNameNew','$stock','$name')";
              if(Query($query));
                echo "<script>window.location.replace('adminInventory.php')</script>";                                
          }
          else
              echo "your file is too big!";
      }
      else
          echo "there was an error uploading your file!";
  }
  else
      echo "you cannot upload files of this type";     
  }
?>

<script>
// for data tables
$(document).ready(function() {
    $('#tbl').DataTable();
});
</script>

<script>
// sidebar toggler
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<script>
// for navbar click locations
document.getElementById("pos").onclick = function() {
    window.location.replace('adminPos.php');
};
document.getElementById("orders").onclick = function() {
    window.location.replace('adminOrders.php');
};
document.getElementById("ordersQueue").onclick = function() {
    window.location.replace('adminOrdersQueue.php');
};
document.getElementById("salesReport").onclick = function() {
    window.location.replace('adminSalesReport.php');
};
document.getElementById("accountManagement").onclick = function() {
    window.location.replace('accountManagement.php');
};
document.getElementById("customerFeedback").onclick = function() {
    window.location.replace('adminFeedbackList.php');
};
document.getElementById("adminTopUp").onclick = function() {
    window.location.replace('adminTopUp.php');
};
</script>

<?php 
    if(isset($_POST['logout'])){
        $dishesArr = array();
        $dishesQuantity = array();
        if(isset($_SESSION['dishes'])){
            for($i=0; $i<count($_SESSION['dishes']); $i++){
                if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                    $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                }
                else{
                    array_push($dishesArr,$_SESSION['dishes'][$i]);
                }
            }
            foreach(array_count_values($_SESSION['dishes']) as $count){
                array_push($dishesQuantity,$count);
            }
            for($i=0; $i<count($dishesArr); $i++){ 
                $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('Login.php');</script>";
    }
?>