<?php 
    $page = 'admin';
    include('method/checkIfAccountLoggedIn.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Inventory - Update</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.css">
    <link rel="stylesheet" href="css/admin.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php">Admin</a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="#" id="pos"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="orders"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="ordersQueue"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
                <li class="mb-2 active">
                    <a href="#"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="salesReport"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="accountManagement"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="#" id="customerFeedback"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-1">
                    <a href="#" id="adminTopUp"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout"><i class="bi bi-power me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list"></i>Dashboard (Inventory Update)
                    </button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <!-- <button class="btn btn-lg btn-dark col-12 mb-4" id="back"><i class="bi bi-arrow-left me-1"></i>BACK </button> -->
                    <?php
                        $idAndPicname = explode(',',$_GET['idAndPicnameUpdate']);    
                        $id = $idAndPicname[0];
                        $dishOriginal = $idAndPicname[1];
                        $priceOriginal = $idAndPicname[2];
                        $picNameOriginal = $idAndPicname[3];
                        $stockOriginal = $idAndPicname[4];
                        $name = $_SESSION['name'];
                    ?>

                    <div class="table-responsive col-lg-12 mb-4">
                        <table class="table table-bordered col-lg-12">
                            <tr>
                                <td><b>DISH:</b></td>
                                <td><?php echo $dishOriginal; ?></td>
                            </tr>
                            <tr>
                                <td><b>PRICE:</b></td>
                                <td><?php echo $priceOriginal; ?></td>
                            </tr>
                            <tr>
                                <td><b>STOCK:</b></td>
                                <td><?php echo $stockOriginal ?></td>
                            </tr>
                            <tr>
                                <td><b>IMAGE (FILE NAME):</b></td>
                                <td><?php echo $picNameOriginal ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="container-fluid">
                        <form method="post" class="form-group" enctype="multipart/form-data">
                            <input type="text" class="form-control form-control-lg mb-3" name="dish" placeholder="ENTER NEW DISH NAME">
                            <input type="number" class="form-control form-control-lg mb-3" name="price" placeholder="ENTER NEW PRICE">
                            <input type="number" class="form-control form-control-lg mb-3" name="stock" placeholder="ENTER NEW NUMBER OF STOCK">
                            <input type="file" class="form-control form-control-lg mb-4" name="fileInput">
                            <button type="button" class="btn btn-lg btn-danger col-12 mb-3" id="cancel"><i class="bi bi-x me-1"></i>CANCEL</button>
                            <button type="submit" class="btn btn-lg btn-success col-12" name="update"><i class="bi bi-arrow-repeat me-1"></i>UPDATE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<?php
    //if update button click
    if(isset($_POST['update'])){
        include('method/query.php');
        $dish = $_POST['dish'] == '' ? $dishOriginal : $_POST['dish'];
        $price = $_POST['price'] == '' ? $priceOriginal : $_POST['price'];
        $stock = $_POST['stock'] == '' ? $stockOriginal : $_POST['stock'];
        //if image didn't change 
        if($_FILES['fileInput']['name'] == ''){
            $updateQuery = "UPDATE WEBOMS_menu_tb SET dish='$dish', price='$price', stock =  '$stock', lastModifiedBy = '$name' WHERE orderType=$id ";   
            if(Query($updateQuery)){
                die ("<script>alert('SUCCESS UPDATING THE DATABASE!'); window.location.replace('adminInventory.php');</script>");       
            }
        }
        $fileName = $_FILES['fileInput']['name'];
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
                    $updateQuery = "UPDATE WEBOMS_menu_tb SET dish='$dish', price='$price', picName = '$fileNameNew', stock =  '$stock', lastModifiedBy = '$name' WHERE orderType=$id ";        
                    if(Query($updateQuery)){
                        echo '<script>alert("SUCCESS UPDATING THE DATABASE!");</script>';       
                        unlink("dishespic/".$picName);                                        
                    }
                    echo "<script>window.location.replace('adminInventory.php');</script>";                                
                }
                else
                    echo "YOUR FILE IS TOO BIG!";
            }
            else
                echo "THERE WAS AN ERROR UPLOADING YOUR FILE!";
        }
        else
            echo "YOU CANNOT UPLOAD FILES OF THIS TYPE!";  
    }
?>

<script>
    document.getElementById("cancel").addEventListener("click",function(){
        window.location.replace('adminInventory.php');
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