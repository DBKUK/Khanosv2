<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] .'/modules/system/login/userchk.php';
// z raporu icin gun kontrolu ve no olusturma...
$today = date('Y-m-d');
$check = $system->sqlsorgu("day_no where Date = '$today' ", "Number");
$number = $system->sqlsorgu("day_no order by Number DESC ", "Number");
// system settings
$deviceid = $system->sqlsorgu("config", "Device_Id");
$CustomerScreen = $system->sqlsorgu("config", "CustomerScreen");
$CustomerScreen_Port = $system->sqlsorgu("config", "CustomerScreen_Port");
$WebAdress = $system->sqlsorgu("config", "WebAdress");
$Mod = $system->sqlsorgu("config", "Mod");
$Print_Inv_Cash = $system->sqlsorgu("config", "Print_Inv_Cash");
$Print_Inv_Card = $system->sqlsorgu("config", "Print_Inv_Card");
$Redirect_Invoice_Page= $system->sqlsorgu("config", "Redirect_Invoice_Page");
$CustomerNameTakeaway= $system->sqlsorgu("config", "CustomerNameTakeaway");
$ZreportTime= $system->sqlsorgu("config", "ZreportTime");
$LogoOnInvoice= $system->sqlsorgu("config", "LogoOnInvoice");
$Voucher= $system->sqlsorgu("config", "Voucher");
$Deposit= $system->sqlsorgu("config", "Deposit");
$Discount= $system->sqlsorgu("config", "Discount");
$OS= $system->sqlsorgu("config", "OS");
//main printer settings
$master_prt= $system->sqlsorgu("printers where Master = 1 ", "System");
$master_type= $system->sqlsorgu("printers where Master = 1 ", "Type");   
if($OS == 0){
	
	$filename = '/dev/usb/lp0';
	$filename1 = '/dev/usb/lp1';
	$filename2 = '/dev/usb/lp2';
	$filename3 = '/dev/usb/lp3';

	if (file_exists($filename)) {
		$lp = "/dev/usb/lp0" ;
	} else if (file_exists($filename1)) {
		$lp = "/dev/usb/lp1" ;
	}else if (file_exists($filename2)) {
		$lp = "/dev/usb/lp2" ;
	} 	else if (file_exists($filename2)) {
		$lp = "/dev/usb/lp3" ;
	}
	

	if ($master_type = 0){
		$master_connector = new FilePrintConnector($lp);
	} else {
$master_ip= $system->sqlsorgu("printers where Master = 1 ", "IP");   

} 
}
else {
//@TODO Add codes for the Windows OS
}








if($check == 0){
	$post['Date'] = $today;
	$post['Number'] = $number + 1;
	$system->islem($post,"day_no","");
}
