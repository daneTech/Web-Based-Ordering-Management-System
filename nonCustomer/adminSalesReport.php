<?php
    $page = 'admin';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    //init
    $_SESSION['from'] = 'adminSalesReport';
    $_SESSION['resultSet'] = array();
    $_SESSION['date1'] =  $_SESSION['date2'] = '';

    //default value
    $query = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = 'complete' ORDER BY b.id asc; ";
    $resultSet =  getQuery2($query); 
    $_SESSION['name'] = getQueryOneVal2("select name from weboms_userInfo_tb where user_id = '$_SESSION[user_id]' ",'name');
    
    //fetch by date
    if(isset($_POST['fetch'])){
        if($_POST['dateFetch1'] != '' && $_POST['dateFetch2'] != ''){
            $date1 = $_POST['dateFetch1'];
            $date2 = $_POST['dateFetch2'];

            $_SESSION['date1'] = date('m/d/Y h:i a ', strtotime($date1));
            $_SESSION['date2'] = date('m/d/Y h:i a ', strtotime($date2));
            $query = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = 'complete' and b.date between '$date1' and '$date2' ORDER BY b.id asc; ";
            $resultSet =  getQuery2($query); 
            $_SESSION['resultSet'] = array();
        }
    }
 
    //show all
    if(isset($_POST['showAll'])){
        $query = "select a.name, b.* from weboms_userInfo_tb a right join weboms_order_tb b on a.user_id = b.user_id where b.status = 'complete' ORDER BY b.id asc; ";
        $resultSet =  getQuery2($query); 
        unset($_POST['dateFetch1']);
        unset($_POST['dateFetch2']);
        $_SESSION['resultSet'] = array();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script type="text/javascript" src="../js/bootstrap 5/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>

    <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
            
            <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li class="mb-2">
                    <a href="adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2 active">
                    <a href="adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="accountManagement.php"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="adminFeedbackList.php"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-2">
                    <a href="adminTopUp.php"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1">
                    <a href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                </li>
            <?php } ?>
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
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> TOGGLE</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center g-3">
                    <div class="btn-group container-fluid" role="group" aria-label="Basic mixed styles example">
                        <button class="btn btn-lg btn-danger" id="viewInPdf"><i class="bi bi-file-pdf"></i> PDF</button>
                    </div>

                    <!-- table -->
                    <form method="post" class="col-lg-12">
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered border-white col-lg-12">
                                <tr>
                                    <td>
                                        <h1 class="fw-normal h3 form-control form-control-lg">FROM:</h1>
                                    </td>
                                    <td>
                                        <input type="datetime-local" name="dateFetch1" class="form-control form-control-lg" value="<?php echo(isset($_POST['dateFetch1'])?  $_POST['dateFetch1']: " ") ?>">
                                    </td>
                                    <td>
                                        <button type="submit" name="fetch" class="btn btn-lg btn-primary col-12"><i class="bi bi-arrow-bar-left"></i> Fetch (between)</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h1 class="fw-normal h3 form-control form-control-lg">TO:</h1>
                                    </td>
                                    <td>
                                        <input type="datetime-local" name="dateFetch2" class="form-control form-control-lg" value="<?php echo(isset($_POST['dateFetch1'])?  $_POST['dateFetch2']: " ") ?>">
                                    </td>
                                    <td>
                                        <button type="submit" name="showAll" class="btn btn-lg btn-primary col-12"><i class="bi bi-list"></i> Show All</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>

                    <!-- table 2 -->
                    <div class="table-responsive col-lg-12">
                        <table class="table table-bordered table-hover col-lg-12">
                            <thead>
                                <tr>
                                    <th scope="col">NAME</th>
                                    <th scope="col">TRANSACTION NO.</th>
                                    <th scope="col">DATE(MM/dd/yyyy) & TIME</th>
                                    <th scope="col">TOTAL ORDER</th>
                                    <th scope="col">ORDER DETAILS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $total = 0;
                                    if($resultSet != null)
                                        foreach($resultSet as $row){ ?>
                                <?php array_push($_SESSION['resultSet'], $row)?>
                                <tr>
                                    <!--if account is deleted block-->
                                    <?php if($row['staffInCharge'] == 'online order' && $row['name'] == '' ){ ?>
                                        <td><a class="text-danger">Deleted Account</a></td>
                                    <?php }elseif($row['name'] != ''){ ?>
                                        <td ><?php echo $row['name']; ?></td>
                                    <?php }else{ ?> 
                                        <td >(No Name)</td>
                                    <?php }?>
                                    <td><?php echo $row['order_id'];?></td>
                                    <td><?php echo date('m/d/Y h:i a ', strtotime($row['date'])); ?></td>
                                    <td><?php echo '₱'. number_format($row['totalOrder'],2); ?></td>
                                    <?php $total += $row['totalOrder'];?>
                                    <!-- order detail -->
                                    <td>
                                        <a class="btn btn-light" style="border:1px solid #cccccc;" href="adminOrder_details.php?order_id=<?php echo $row['order_id']?>"><i class="bi bi-list"></i> View</a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="3"><strong>Total Amount:</strong></td>
                                    <td><strong><?php echo '₱'. number_format($total,2);?></strong></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>


<script>
//order button (js)
document.getElementById("viewInPdf").addEventListener("click", () => {
    if (<?php echo $resultSet == null ? 'true':'false';?>) {
        alert('PDF is empty!');
        return;
    } else {
        window.open("../pdf/salesReport.php");
    }
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
                $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query2($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>