<?php
declare(strict_types=1);
use Ghostff\TextToImage\Text;
use Ghostff\TextToImage\TextToImage;
require_once __DIR__ . '/src/TextToImage.php';
require_once __DIR__ . '/src/Text.php';


// Clearing previous letters generated when you visit
// https://impact2022.org/letter/?purge=yes

if(isset($_GET['purge'])){
//	if (date('Hi') == "0300") {
	if ($_GET['purge'] == "yes") {
		$files = glob(__DIR__ .'/temp/*');
		foreach($files as $file){
		  if(is_file($file)) {
			unlink($file);
		  }
		}
	}
}
//change the value below 1 if you intend to store details in database
$use_database = 0;

if(isset($_GET['setlocal']) and $_GET['setlocal'] == 'yes'){
	$allowLocation = 1;
}else{
	$allowLocation = 0;
}

if(isset($_GET['setDate']) and $_GET['setDate'] == 'yes'){
	$customDateLocal = 1;
}else{
	$customDateLocal = 0;
}


$states = ["Abia","Adamawa","Akwa Ibom","Anambra","Bauchi","Bayelsa","Benue","Borno","Cross River","Delta","Ebonyi","Edo","Ekiti","Enugu","FCT - Abuja","Gombe","Imo","Jigawa","Kaduna","Kano","Katsina","Kebbi","Kogi","Kwara","Lagos","Nasarawa","Niger","Ogun","Ondo","Osun","Oyo","Plateau","Rivers","Sokoto","Taraba","Yobe","Zamfara","Not Applicable"];

if(isset($_POST['getLetter'])){

	$name = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
	@$location = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	@$location2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
	@$date = filter_var(str_replace('','',$_POST['setDate']), FILTER_SANITIZE_STRING);

	@$phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
	@$state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);

	$filename = "/temp/letter4-".str_replace(' ','-', $name);

	$shareURL = $_SERVER['SERVER_NAME'] . $filename .".jpg";

	$text  = Text::from("Dear ".$name.",")
		        ->position(55, 410)
		        ->font(24, __DIR__ . '/sweet purple.otf')
		        ->color(23,62, 95);

	$text4  = Text::from($date.", 2022 at ")
		        ->position(350, 891)
		        ->font(16, __DIR__ . '/kalypsa-medium.ttf')
		        ->color(23,62, 95);

	$text2  = Text::from($location)
		        ->position(570, 891)
		        ->font(16, __DIR__ . '/kalypsa-medium.ttf')
		        ->color(23,62, 95);

	$text3  = Text::from($location2)
		        ->position(55, 922)
		        ->font(16, __DIR__ . '/kalypsa-medium.ttf')
		        ->color(23,62, 95);



	if($allowLocation == 1 && $customDateLocal == 1){
		(new TextToImage(__DIR__ . '/letter3.jpg'))->addTexts($text, $text2, $text3, $text4)->render(__DIR__ . $filename.'.jpg');
	}
	elseif($allowLocation == 1){
		(new TextToImage(__DIR__ . '/letter2.jpg'))->addTexts($text, $text2, $text3)->render(__DIR__ . $filename.'.jpg');
	}
	elseif($allowLocation == 0){
		(new TextToImage(__DIR__ . '/letter.jpg'))->addTexts($text)->render(__DIR__ . $filename.'.jpg');
	}


	if($use_database == 1){
		$db_host = 'localhost'; // database host
		$db_user = 'root';		// database user
		$db_pass = '';			// database password
		$db_name = 'test';		// database name
		$db_table = 'invite';	// database table to be used
		$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
		$insert = "INSERT INTO `invite` (`id`, `fullname`, `phone`, `state`) VALUES (NULL, '$name', '$phone', '$state')";
		if (!$conn) {
		  	$feedback = "Database Connection Failed!";
		}
		if (mysqli_query($conn, $insert)) {
			$feedback = "Record Saved!";
		} else {
			$feedback =  "Error Saving Record!";// . mysqli_error($conn);
		}
	}

}

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="DCLM SMAT">
<meta name="generator" content="Hugo 0.88.1">
<title>Invitation to Global Youth Convocation</title>

<!-- Bootstrap core CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Favicons -->
<link rel="apple-touch-icon" href="dclm-logo.png" sizes="120x120">
<link rel="icon" href="dclm-logo.png" sizes="120x120" type="image/png">
<meta name="theme-color" content="#7952b3">


<style>
html,body { height: 100%;}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}
body {background: #f5f5f5 url(./gs-bg.jpg) no-repeat top right /cover;}

.bd-placeholder-img {
font-size: 1.125rem;
text-anchor: middle;
-webkit-user-select: none;
-moz-user-select: none;
user-select: none;
}
@media (min-width: 768px) {
.bd-placeholder-img-lg {
  font-size: 3.5rem;
}
}

.form-getLetter {
	width: 100%;
	max-width: 330px;
	padding: 15px;
	margin: auto;
	background-color: rgb(121 94 149 / 45%);
	border-radius: 5px;
}

.form-getLetter .form-floating:focus-within {
  z-index: 2;
}

.form-getLetter input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-getLetter input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.toasts-top-right.fixed {
    position: fixed;
    font-size: 12px;
}
.toast {
    width: 350px;
    max-width: 100%;
    font-size: 12px !important;
}
.toast-header {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    padding: .25rem .75rem;
    color: #6c757d;
    background-color: rgba(255,255,255,.85);
    background-clip: padding-box;
    border-bottom: 1px solid rgba(0,0,0,.05);
    border-top-left-radius: calc(.25rem - 1px);
    border-top-right-radius: calc(.25rem - 1px);
}
.toasts-top-right {
    position: absolute;
    left: 0;
    top: 0;
    z-index: 1040;
}
.toast-header .btn-close {
    background: none;
    padding: 0;
    background-color: transparent;
    border: 0;
}
.letter-box img {
    object-fit: cover;
    width: auto;
    height: 380px;
}
.bg-fb{background-color:#6045B6}
.bg-tw{background-color:#46A5E7}
.bg-wa{background-color:#00BD29}
.btn{color: #fff !important}
</style>
</head>
  <body class="text-center">

	<main class="form-getLetter">
	  <form action="" method="POST">
		<img class="mb-4" src="dclm-logo.png" alt="" width="72">
		<h1 class="h6 mb-3 fw-bold text-light">Please enter your invitee's details</h1>
		<?php //print date('Hi') ;?>
		<div class="form-floating">
		  <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname">
		  <label for="fullname">Fullname</label>
		</div>

		<?if($allowLocation == 1){?>
		<div class="form-floating mt-1">
		  <input type="text" class="form-control" id="address" name="address" maxlength="34" placeholder="Address Line 1">
		  <label for="address">Address Line 1</label>
		</div>
		<div class="form-floating mt-1">
		  <input type="text" class="form-control" id="address2" name="address2" maxlength="70" placeholder="Address Line 2">
		  <label for="address2">Address Line 2</label>
		</div>
		<? } ?>

		<?if($customDateLocal == 1){?>
		<div class="form-floating mt-1">
		  <input type="text" class="form-control" id="setDate" name="setDate" maxlength="34" placeholder="Event Date(ex: January 5 - 6)">
		  <label for="setDate">Event Date(ex: January 5 - 6)</label>
		</div>
		<? } ?>

		<?if($use_database == 1){?>
		<div class="form-floating">
		  <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number">
		  <label for="phone">Phone Number</label>
		</div>

		<div class="form-floating">
		  <select class="form-select" id="state" name="state">
			<option selected disabled>Select State</option>
			<?php foreach($states as $state){?>
				<option><?php print $state;?></option>
			<? } ?>
		  </select>
		  <label for="state" class="form-label">Select State:</label>
		</div>
		<? } ?>
		<button name="getLetter" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Submit</button>
		<a href="./?setlocal=yes" class="btn btn-sm btn-danger mt-1">Get a Letter with Custom Location</a>
		<a href="./?setlocal=yes&setDate=yes" class="btn btn-sm btn-warning mt-1">Letter with Custom Date & Location</a>
	  </form>
	</main>

<?php if(isset($_POST['getLetter'])){ ?>
<div id="toastsContainerTopRight" class="toasts-top-right fixed">

	<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
	  <div class="toast-header">
		<img class="rounded me-2" rounded me-2 src="dclm-logo.png" alt="" width="20">
		<strong class="me-auto">Download Letter</strong>
		<small>Subtitle</small>
		<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
	  </div>
	  <div class="toast-body">
	  	<p class="mx-2">Click on the button below to Download Letter for <b><?php @print $name?></b></p>
	  	<a href=".<?php print $filename?>.jpg" target="_blank" class="btn btn-success btn-sm mb-1 me-auto" download>Download Letter</a>
	  	<span class="btn btn-danger btn-sm m-auto mb-1 closeToast" data-bs-dismiss="toast">Close</span>
		<div class="letter-box">
			<img src=".<?php print $filename?>.jpg" class="m-auto border rounded">
		</div>
	  </div>
	</div>
</div><!--//#toastsContainerTopRight-->
<? } ?>

<script>
$(document).ready(function(){
	$('.toast').toast('show');
    $('.closeToast').click(function(){
		$(".toast").toast("hide");
    });
});
</script>

<!-- Measureing Usage with Analytics -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3Y3WM2L61S"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-3Y3WM2L61S');
</script>

  </body>
</html>











