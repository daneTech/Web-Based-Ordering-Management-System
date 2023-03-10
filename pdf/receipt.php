<?php 
    $page = 'receipt';
    include('../method/checkIfAccountLoggedIn.php');
    require_once('../TCPDF-main/tcpdf.php'); 
    if($_SESSION['refreshCount'] < 2){
        $_SESSION['refreshCount'] += 1;
        die ("<script>window.location.replace('receipt.php');</script>");
    }

    // init
    $date = date('m/d/Y h:i:s a ', strtotime($_SESSION['date']));
    $cash = $_SESSION['cash'];
    $total = $_SESSION['total'];
 
    $change =  $cash-$total;
    $cash = number_format($cash,2);
    $total = number_format($total,2);
   
    $change = number_format($change,2);
    $dishesArr = $_SESSION['dishesArr'];
    $priceArr = $_SESSION['priceArr'];
    $dishesQuantity = $_SESSION['dishesQuantity'];
 
    // pdf proccess
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle("Receipt");  
    // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(1, 1, 1);  
    // $pdf->setPrintHeader(false);  
    // $pdf->setPrintFooter(false);  
    $pdf->SetAutoPageBreak(FALSE, 1);  

    $pdf->AddPage('P','A6');
    $pdf->SetFont('dejavusans', 'B', 18);  
    $pdf -> Cell(0,15,"Order#$_SESSION[order_id]", 0, 1,'C');

    $pdf->SetFont('dejavusans', '', 13);  
    $pdf -> Cell(0,5,"$_SESSION[companyName]", 0, 1,'C');
    $pdf -> Cell(0,5,"$_SESSION[companyAddress]", 0, 1,'C');
    $pdf -> Cell(0,5,"$_SESSION[companyTel]", 0, 1,'C');
    $pdf -> ln(3);
    $pdf -> Cell(0,5,"$date", 0, 1,'C');
    $pdf -> ln(5);

    $pdf -> Cell(20,10,"QTY.",'B,T', 0,'C');
    $pdf -> Cell(40,10,"Dish",'B,T', 0,'L');
    $pdf -> Cell(0,10,"Price",'B,T', 0,'R');
    $pdf -> ln(10); 
    for($i=0; $i<count($dishesArr); $i++){ 
        $price = number_format($priceArr[$i],2);
        $pdf -> Cell(20,10,"$dishesQuantity[$i]",'',0,'C');
        $pdf -> Cell(40,10,"$dishesArr[$i]",'', 0,'L');
        $pdf -> Cell(0,10,"₱$price",'',0,'R');
        $pdf -> ln(5);
    }
    $pdf -> ln(8);

    $pdf->SetFont('dejavusans', 'B', 18);  
    $pdf -> Cell(70,10,"Total",'T','0','L');
    $pdf -> Cell(33,10,"₱$total",'T','0','R');
    $pdf -> ln(7);
    $pdf->SetFont('dejavusans', '', 13);  
    $pdf -> Cell(70,10,"Payment",'','0','L');
    $pdf -> Cell(33,10,"₱$cash",'','0','R');
    $pdf -> ln(6);
    $pdf -> Cell(70,10,"Change",'','0','L');
    $pdf -> Cell(33,10,"₱$change",'','0','R');
    $pdf -> ln(13);
    
    $pdf -> Cell(0,10,"Customer: $_SESSION[customerName]" ,'T','0','L');
    $pdf -> ln(5);
    if($_SESSION['staffInCharge'] == 'online order'){
        $pdf -> Cell(0,10,"Order Type: ONLINE ORDER",'','0','L');
    }
    else{
        $pdf -> Cell(0,10,"Order Type: POS",'','0','L');
    }
    $pdf -> ln(5);
    $pdf -> Cell(0,10,"Order Number: $_SESSION[or_number] ",'','0','L');
    $pdf -> ln(5);
    if($_SESSION['staffInCharge'] != 'online order'){
        $pdf -> Cell(0,10,"Cashier: $_SESSION[name]",'','0','L');
    }
    $pdf -> ln(15);

    // $pdf->SetFont('dejavusans', '', 13);  
    $pdf -> Cell(0,5,"THANK YOU FOR YOUR PURCHASE!", 0, 1,'C');

    ob_end_clean();
    $pdf->Output('file.pdf', 'I');

    if($_SESSION['fromReceipt'] == 'pos'){
        $_SESSION["dishes"] = $_SESSION["price"] = $_SESSION["orderType"] = $_SESSION['multiArr'] = array(); 
        $_SESSION['fromReceipt'] =  $_SESSION['total'] = $_SESSION['cash'] = $_SESSION['order_id'] = null;
    }
  
?>