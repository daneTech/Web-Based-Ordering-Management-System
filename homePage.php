<!DOCTYPE html>
<html>
    <head><title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    </head>
<body>
<div class="container text-center">
        <button type="button" class="btn-success col-sm-1" id="logout">Logout</button>

    </form>
</body>
</html>
<?php 
    $username = $_GET['username'];
    echo "<script>alert('$username');</script>";
?>
<script>
        document.getElementById("logout").addEventListener("click",function(){
        window.location.replace('login.php');
    });
</script>