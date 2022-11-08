<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container text-center">

        <button class="btn btn-success col-sm-4" id="admin">Admin</button>
        <script>
            document.getElementById("admin").onclick = function () {window.location.replace('admin.php'); };    
        </script> 
        
        <div class="col-lg-12">
            <table class="table table-striped" border="10">
            <tr>	
            <th scope="col">name</th>
            <th scope="col">
              <form method="post">
                <button type="submit" name="showAll" style="font-size: 12px ;">Show/Unshow All</button>
              </form>
              </br>
              Orders ID
            </th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col">Approve status:</th>
            <th scope="col">Order status:</th>
            <th scope="col">Date:</th>
            </tr>
              <tbody>
                <?php
                session_start();
                include_once('orderClass.php');
                $orderlist = new orderList();
                if($_SESSION['query'] != 'all')
                  $resultSet =  $orderlist -> getNotOrdersComplete();
                else
                  $resultSet =  $orderlist -> getOrderList();
                if($resultSet != null)
                foreach($resultSet as $rows){ ?>
                <tr>	   
                <td><?php echo $rows['name']; ?></td>
                <td><?php echo $rows['ordersLinkId'];?></td>
                <td><a href="adminOrders.php?idAndPic=<?php echo $rows['ordersLinkId'].','.$rows['proofOfPayment']?>">View Order</a></td>
                <td><?php 
                if($rows['status'] == 1){
                  echo "Already Approved";
                }
                else{
                  ?><a href="?status=<?php echo $rows['ordersLinkId'].','.$rows['email']; ?>">Approve</a><?php
                }?>
                </td>
                <td><a href="?orderComplete=<?php echo $rows['ordersLinkId'] ?>">Order Complete</a></td>
                <td><a href="method/deleteOrderMethod.php?idAndPicnameDelete=<?php echo $rows['ID'].','.$rows['proofOfPayment'].','.$rows['ordersLinkId'] ?>">Delete</a></td>
                <td><?php echo ($rows['status'] == 1 ? "Approved": "Pending"); ?></td>
                <td><?php echo ($rows['isOrdersComplete'] == 1 ? "Order Complete": "Preparing"); ?></td>
                <td><?php echo date('m/d/Y h:i a ', strtotime($rows['date'])); ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
	    </div>
    </body>
</html>
<?php 
  if(isset($_GET['status'])){
    $arr = explode(',',$_GET['status']);  
    $ordersLinkId = $arr[0];
    $email = $arr[1];
    $order = new order($ordersLinkId,$email);
    $order-> computeOrder(); 
    $order-> sendReceiptToEmail(); 
    $order-> approveOrder();
  }
  if(isset($_GET['orderComplete'])){
    $id = $_GET['orderComplete'];
    $orderlist =  orderList::withID($id);
    $orderlist -> setOrderComplete();
  }
  if(isset($_POST['showAll'])){
    if($_SESSION['query'] == 'all')
      $_SESSION['query'] = null;
    else
      $_SESSION['query'] = 'all';
    echo "<script>window.location.replace('adminOrdersList.php');</script>";
  }
?>