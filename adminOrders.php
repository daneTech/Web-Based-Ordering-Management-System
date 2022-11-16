<!DOCTYPE html>
<html>
<head>
  <title>Admin Orders - View Orders</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 

</head>
<body class="bg-light">

<div class="container text-center">
  <div class="row justify-content-center">
    <h1 class="font-weight-normal mt-5 mb-4 text-center">View Orders</h1>
        
    <div class="col-lg-12 cont2">
          <button class="btn btn-lg btn-danger col-12 mb-3" id="orderList">Order List</button>
          <button class="btn btn-lg btn-success col-12 mb-4" id="salesReport">Sales Report</button>

          <div class="table-responsive col-lg-12">
            <?php 
              $arr = explode(',',$_GET['idAndPic']);
              $id = $arr[0];
              $pic = $arr[1];
              include_once('class/transactionClass.php');
              include('method/Query.php');
              $order = new transactionById( $id );  
              $arr =  $order -> getAllOrderById(); 
            ?>
            <table class="table table-striped table-bordered border-dark col-lg-12">
              <thead class="table-dark">
                <tr>	
                  <!-- <th scope="col">price</th> -->
                  <th scope="col">QUANTITY</th>
                  <th scope="col">NAME</th>
                  <th scope="col">PRICE</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $total = 0;
                if($arr != null)
                foreach($arr as $rows){ ?>
                <tr>	   
                  <?php $price = ($rows['price']*$rows['quantity']);  $total += $price;?>
                  <td><?php echo $rows['quantity']; ?></td>
                  <td><?php echo $rows['dish']; ?></td>
                  <td><?php echo '₱'.$price?></td>
                </tr>
                <?php }?>
                <tr>
                  <td colspan="2"><b>TOTAL AMOUNT:</b></td>
                  <td><b>₱<?php echo $total?></b></td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="mb-5">
            <h1 class="font-weight-normal">PROOF OF PAYMENT:</h1>
            <?php echo "<img src='payment/$pic' style=width:300px;height:500px>";?>
          </div>
	  </div>
	</div>
</div>

</body>
</html>

<script>
  document.getElementById("orderList").onclick = function () {window.location.replace('adminOrdersList.php'); };
  document.getElementById("salesReport").onclick = function () {window.location.replace('adminSalesReport.php'); };
</script> 