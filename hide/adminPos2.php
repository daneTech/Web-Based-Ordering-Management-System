<?php 
  $page = 'cashier';
  include('method/checkIfAccountLoggedIn.php');
  include('method/query.php');
  if(!isset($_SESSION["dishes"]) && !isset($_SESSION["price"])){
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array(); 
    $_SESSION["orderType"] = array(); 
  }
  $_SESSION['refreshCount'] = 0;
  $_SESSION['multiArr'] = array();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin POS</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

</head>

<body class="bg-light">
    <div class="container text-center mt-5">
        <div class="row justify-content-center">
            <?php if($_SESSION['accountType'] != 'cashier'){?>
            <button class="btn btn-lg btn-dark col-12 mb-4" id="admin">Admin</button>
            <?php }else{?>
            <form method="post" class="col-6"><button name="logout" class="btn btn-lg btn-danger col-12 mb-4"
                    id="logout">Logout</button></form>
            <?php }?>

            <script>
            document.getElementById("admin").onclick = function() {
                window.location.replace('admin.php');
            };
            </script>
            <script>
            document.getElementById("viewCart").onclick = function() {
                window.location.replace('adminCart.php');
            };
            </script>
            <!-- logout -->
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
            <!-- table container -->
            <div class="table-responsive col-lg-6">
                <?php 
                $query = "select * from WEBOMS_menu_tb";
                $resultSet =  getQuery($query)
            ?>
                <table id="tbl" class="table table-striped table-bordered mb-5 col-lg-12">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">DISH</th>
                            <th scope="col">PRICE</th>
                            <th scope="col">STOCK</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
          if($resultSet != null)
            foreach($resultSet as $rows){ ?>
                        <tr>
                            <td><?=$rows['dish']?></td>
                            <td><?php echo number_format($rows['price'],2); ?></td>
                            <td><?php echo $rows['stock']; ?></td>
                            <td><a class="btn btn-light border-dark" <?php   if($rows['stock'] <= 0) 
                                echo "<button>Out of stock</button>";
                            else{
                    ?> href="?order=<?php echo $rows['dish'].",".$rows['price'].",".$rows['orderType']?>">Add To
                                    Cart</a><?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- 2nd table container -->
            <div class="table-responsive col-lg-6">
                <table class="table table-striped table-bordered col-lg-12 mb-4 mt-5">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">DISH</th>
                            <th scope="col">QUANTITY</th>
                            <th scope="col">PRICE</th>
                            <th scope="col" colspan="1">Option</th>
                        </tr>
                    </thead>
                    <?php 
                    $dishesArr = array();
                    $priceArr = array();
                    $dishesQuantity = array();
                    $orderType = array();
      
                    //merge repeating order into 1 
                    for($i=0; $i<count($_SESSION['dishes']); $i++){
                        if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                            $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                            $newCost = $priceArr[$index] + $_SESSION['price'][$i];
						                $priceArr[$index] = $newCost;
                        }
                        else{
                            array_push($dishesArr,$_SESSION['dishes'][$i]);
                            array_push($priceArr,$_SESSION['price'][$i]);
                            array_push($orderType,$_SESSION['orderType'][$i]);
                        }
                    }
                    //push order quantity into arrray
                    foreach(array_count_values($_SESSION['dishes']) as $count){
                        array_push($dishesQuantity,$count);
                    }
                    
                    //merge 3 array into 1 multi dimensional
                    for($i=0; $i<count($dishesArr); $i++){ 
                        $arr = array('dish'=> $dishesArr[$i], 'price' => $priceArr[$i], 'quantity' => $dishesQuantity[$i], 'orderType' => $orderType[$i]);
                        array_push($_SESSION['multiArr'],$arr);
                    }
                    //sort multi dimensional
                    sort($_SESSION['multiArr']);
                    $total = 0;
                    for($i=0; $i<count($priceArr); $i++){
                        $total += $priceArr[$i];
                    }

                    //create a table using the multi dimensional array
                    foreach($_SESSION['multiArr'] as $arr){ ?>
                    <tr>
                        <td><?php echo $arr['dish'];?></td>
                        <td><?php echo $arr['quantity'];?></td>
                        <td><?php echo '₱'.number_format($arr['price'],2);?></td>
                        <td>
                            <!-- check stock -->
                            <?php if(getQueryOneVal("select stock from WEBOMS_menu_tb where dish = '$arr[dish]' ",'stock') > 0) { ?>
                            <a class="btn btn-success border-dark"
                                href="?add=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>">+</a>
                            <?php }else{ ?>
                            <a class="btn btn-success border-dark">Out of Stock</a>
                            <?php } ?>
                            <a class="btn btn-success border-dark"
                                href="?minus=<?php echo $arr['dish'].','.($arr['price']/$arr['quantity']).','.$arr['orderType']; ?>">-</a>
                        </td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="2"><b>TOTAL AMOUNT:</b></td>
                        <td><b>₱<?php echo number_format($total,2); ?></b></td>
                    </tr>
                </table>
                <form method="post">
                    <input id="cashNum" name="cash" min="<?php echo $total;?>" step=any placeholder="Cash Amount"
                        type="number" class="form-control form-control-lg mb-3" required></input>
                    <button id="orderBtn" type="submit" class="btn btn-lg btn-success col-12 mb-3" name="order">Place
                        Order</button>
                </form>
                <form method="post">
                    <button type="submit" id="clear" class="btn btn-lg btn-danger col-12 mb-5" name="clear">Clear
                        Order</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>

<?php 
    //clear button
    if(isset($_POST['clear'])){
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query($updateQuery);    
        }
        $_SESSION["dishes"] = array();
        $_SESSION["price"] = array();
        $_SESSION["orderType"] = array(); 
        echo "<script>window.location.replace('adminPos.php');</script>";
    }
    
    //add to cart
    if(isset($_GET['order'])){
      $order = explode(',',$_GET['order']);  
      $dish = $order[0];
      $price = $order[1];
      $orderType = $order[2];
      array_push($_SESSION['dishes'], $dish);
      array_push($_SESSION['price'], $price);
      array_push($_SESSION['orderType'], $orderType);

      $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
      if(Query($updateQuery))
        echo "<script>window.location.replace('adminPos.php');</script>";    
    }

      //add
    if(isset($_GET['add'])){
        $arr = explode(',',$_GET['add']);
        $dish = $arr[0];
        $price = $arr[1];
		    $orderType = $arr[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);

        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock - 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
          echo "<script>window.location.replace('adminPos.php');</script>";    
    }

    //minus
    if(isset($_GET['minus'])){
        $arr = explode(',',$_GET['minus']);
        $dish = $arr[0];
        $price = $arr[1];
        $orderType = $arr[2];
       
        //remove one order 
        $key = array_search($dish, $_SESSION['dishes']);
        unset($_SESSION['dishes'][$key]);
        unset($_SESSION['price'][$key]);
        unset($_SESSION['orderType'][$key]);

        //refresh the array
        $_SESSION['dishes'] = array_values($_SESSION['dishes']);
        $_SESSION['price'] = array_values($_SESSION['price']);
        $_SESSION['orderType'] = array_values($_SESSION['orderType']);

        $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + 1) WHERE dish= '$dish' ";    
        if(Query($updateQuery))
            echo "<script>window.location.replace('adminPos.php');</script>";    
    }


    //order button (php)
    if(isset($_POST['order'])){
        $cash = $_POST['cash'];
        if($cash >= $total && $total != 0){
            $_SESSION['continue'] = true;
            date_default_timezone_set('Asia/Manila');
            $date = new DateTime();
            $today =  $date->format('Y-m-d'); 
            $todayWithTime =  $date->format('Y-m-d H:i:s'); 
            $_SESSION['date'] = $todayWithTime;
            $_SESSION['cash'] = $cash;
            $_SESSION['total'] = $total;
            $_SESSION['dishesArr'] = $dishesArr;
            $_SESSION['priceArr'] = $priceArr;
            $_SESSION['dishesQuantity'] = $dishesQuantity;
            $staff = $_SESSION['name'].'('.$_SESSION['accountType'].')';
            $user_id = $_SESSION['user_id'];
            $order_id = uniqid();
            $_SESSION['order_id'] = $order_id;
            $query1 = "insert into WEBOMS_order_tb(user_id, status, order_id, date, totalOrder, payment,  staffInCharge) values('$user_id','prepairing','$order_id','$todayWithTime','$total','$cash', '$staff')";
            for($i=0; $i<count($dishesArr); $i++){
                $query2 = "insert into WEBOMS_ordersDetail_tb(order_id, quantity, orderType) values('$order_id',$dishesQuantity[$i], $orderType[$i])";
                Query($query2);
            }
            Query($query1);
            echo "<script>document.getElementById('clear').click();</script>";
        }   
    }
?>



<script>
//order button (js)
var orderBtn = document.getElementById("orderBtn");
orderBtn.addEventListener("click", () => {
    var num = document.getElementById("cashNum").value;
    if (<?php echo $total == 0 ? 'true':'false';?>) {
        alert('Please place your order!');
        return;
    }
    if (num >= <?php echo $total;?>) {
        alert("Sucess Placing Order!");
        window.open("pdf/receipt.php");
    }
});
</script>

<script>
$(document).ready(function() {
    $('#tbl').DataTable();
});
</script>