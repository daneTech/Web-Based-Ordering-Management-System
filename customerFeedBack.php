<?php 
    $page = 'customer';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feedback</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap 5/bootstrap.min.css"> 
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
    
<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <button class="btn btn-lg btn-dark col-12 mb-3" id="orderList">Order List</button>
        <script>document.getElementById("orderList").onclick = function () {window.location.replace('customerOrders.php'); }; </script> 
            
        <div class="col-lg-12">
            <form method="post">
                <textarea type="text" name="feedback" placeholder="Enter your feedback" class="form-control form-control-lg mb-3" rows="5" required></textarea>
                <button type="submit" name="submit" class="btn btn-lg btn-success col-12"><i class="bi bi-box-arrow-right me-1"></i>SUBMIT</button>
            </form>
        </div>
	</div>
</div>
    
</body>
</html>

<?php 
    if(isset($_POST['submit'])){
        $arr = explode(',',$_GET['ordersLinkIdAndUserLinkId']);
        $order_id = $arr[0];
        $user_id = $arr[1];
        $feedback = $_POST['feedback'];
        $query = "insert into WEBOMS_feedback_tb(feedback, order_id, user_id) values('$feedback', '$order_id', '$user_id')";
        if(Query($query))
            echo "<script>alert('FEEDBACK SENT THANKS!'); window.location.replace('customerOrders.php');</script>";
    }
?>