<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<?php
//support for postgres 8.3
//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
//header("Expires: Sat, 26 Jul 2012 05:00:00 GMT"); // Date in the past
//Author:vbtamil@yahoo.com
//Date:7-Sep-2013
// debug_print_backtrace(); //to get the stack trace
ini_set('display_errors',1); 
ini_set('error_reporting', E_ALL);
//include "include/Authenticate.php";
include '../includes/postfields.php';
include '../includes/cityStatesIndia.php';
include '../includes/countries.php';
include '../includes/posgresqlsupport.php';
include '../includes/serverInfo.php';
//include '../includes/view.php';
global $srchrow;
global $srch;	
global $prepsrch;
global $sql;
global $message;
global $disabled;
global $d;
global $m;
global $Y;
global $bInsert;
global $oper;
$disable=false;
$srchrow=array();
$disabled='disabled';
$heading="Address Book";

//print_r($srchrow['salutation']); echo "<br />";

function validateFields() {
global $message;
  		$message="";
		$email = $_POST['contact_email'];
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){ 
		} else { 
				$message= $message . "Contact Email  Invalid.";
		} 
		$email="";
 		
		if ( empty($_POST['salutation'])) {
			$message= $message . "Saluatation Required.";
		}
		if (empty($_POST['last_name'])) {
			$message= $message . "Last Name Required.";
		} 
		if (empty($_POST['first_name'])) {
			$message= $message . "First Name Required.";
		} 
	
		if (empty($_POST['address_1'])) {
			$message= $message . "Address1 Required.";
		}
		if (empty($_POST['home_phone'])) {
			$message= $message . "Home Phone Required. ";
		}
		if (empty($_POST['work_phone'])) {
			$message= $message . "Work Phone Required.";
		}
		if (empty($_POST['contact_email'])) {
			$message= $message . "Contact Email  Required.";
		}
		
		if (empty($_POST['birth_date'])) {
			$message= $message . "Date of Birth Required.";
		}		
		if (empty($_POST['city']))  {
			$message= $message . "City Required.";
		}		
 		if (empty($_POST['state']))  {
			$message= $message . "State Required.";
		}
 		if (empty($_POST['postal_code'])) {
			$message= $message . "Postal Code	 Required.";
		}		
		 if (empty($_POST['country'])) {
			$message= $message . "Country Required.";
		}

		//echo "<Pre>";print_r($_POST);echo "</Pre>";
		if (empty($message)) {
			$message='validation passed';			
			}
 return $message;
}
function addressDelete(){	
	global $srchrow;
	global $message;
	$table='people';

	$data=array();
	if (!empty($_POST['home_phone'])) {
		$data['home_phone']=$_POST['home_phone'];
	}
	if (!empty($_POST['home_phone'])) {
		$data['contact_email']=$_POST['contact_email'];
	}
	
	$srchrow['record_status']="D";	
	//var_dump($srchrow);echo "<br />";
	$db=getConnection();
	$res = pg_update($db, $table, $srchrow, $data);	
	if ($res) {
		$message='Deleted';
	}
	return;
}
function addressEdit($By) {
	global $srchrow;
	global $message;
	$table='people';
	
	$data=array();
	
	if (!isset($srchrow) or empty($srchrow) ){
	
			foreach ($_POST as $key => $v) {
				$srchrow[$key]="";
				$_POST[$key]="";							
			}			
	}
	if ($By == 'phone') {
		if (isset($_POST['home_phone'])  and !empty($_POST['home_phone'])  ) {
			$data['home_phone']=$_POST['home_phone'];
		}
	} 
	if ($By =='email') {	
		 if (isset($_POST['contact_email']) and !empty($_POST['contact_email'])){
			$data['contact_email']=$_POST['contact_email'];
		}
	}
	$srchrow['record_status']="U";	
	$db=getConnection();
	//echo "<pre>";print_r($srchrow);echo "</pre>";
	$res = pg_update($db, $table, $srchrow, $data);	
	if ($res){
		$message='Updated';
	}
	
	return;
}

function addressInsert() {
	global $srchrow;
	global $message;
	$db=getConnection();
	$table='people';
	$srchrow=arrayCopy($_POST);
	unset($srchrow['submit']);
	
	try {
		//echo "<Pre>";print_r($srchrow);echo "</Pre>";
		$_POST['record_status']='I';
		$srchrow['record_status']='I';
		$res = pg_insert($db, $table, $srchrow) or die(); 
	}catch (Exception $e)
	{
		$message = pg_last_error();
	}
	if ($res) {
		$message='Inserted';
	}
	return;	
}				
function arrayCopy( array $array ) {
        $result = array();
        foreach( $array as $key => $val ) {
		  
            if( is_array( $val ) ) {
                $result[$key] = arrayCopy( $val );
            } elseif ( is_object( $val ) ) {
                $result[$key] = clone $val;
			} else {
                $result[$key] = $val;
            }
        }
        return $result;
}
if (!isset($_POST['submit']) or empty($_POST['submit'])) {
	initSrchRow();	
}
if (isset($_POST['submit'])) {
		saveSrchRow();
	if ($_POST['submit'] == 'SaveByEmail'){
		$heading="Save By Email";
		$message=validateFields();
		if (isset($_POST['contact_email']) and !empty($_POST['contact_email']) ) {
			$sql = "select address_id from people where contact_email='" . $_POST['contact_email'] . "'";
			try {
				$db=getConnection();
				$res=pg_query($db,$sql);		
				$row=pg_fetch_row($res);		
				//echo "<Pre>";print_r($sql);echo "</Pre><P>";
				//echo "<Pre>";print_r($row);echo "</Pre><P>";
				if ($message === 'validation passed'){		
					if (isset($row) and $row>0) {		
						addressEdit("email");						
					}	
					else {
						$message="Not Updated - Reason: email address does not exist";
					}
				} 				
			} catch(Exception $e) {
				die(pg_last_error());
			}		
		}		
		
	}//ByEmail
	
	if ($_POST['submit'] == 'Save'){
		unset($res);		
		$heading="Save By Phone";						
		$message=validateFields();
		if (isset($_POST['home_phone']) and !empty($_POST['home_phone']) ) {
			$sql = "select address_id from people where home_phone='" . $_POST['home_phone'] . "'";
			try {
				$db=getConnection();
				$res=pg_query($db,$sql);		
				$row=pg_fetch_row($res);		
				//echo "<Pre>";print_r($sql);echo "</Pre><P>";
				//echo "<Pre>";print_r($row);echo "</Pre><P>";
				if ($message === 'validation passed'){		
					if (isset($row) and $row>0) {		
						addressEdit("phone");						
					}	
					else {
						$message="Record Not Updated";
					}
				} 				
			} catch(Exception $e) {
				die(pg_last_error());
			}		
		}
		//clear form fields
		foreach ($srchrow as $key=>$value) {
			$_POST[$key]="";
			$srchrow[$key]="";
		}
	}//save by phone
}//submit

if (isset($_POST['submit'])) {
	if ($_POST['submit'] == 'Search'){
		$record_id=0;
		
		$srchBy="";
		$message="";
		$heading="Search";
		
		//var_dump($_POST['last_name']);echo "<br />";
		//var_dump($_POST['first_name']);echo "<br />";
		//var_dump($_POST['middle_name']);echo "<br />";
		$srchrow=arrayCopy($_POST);
		unset($srchrow['submit']);
		
		if (isset($_POST['home_phone']) and !empty($_POST['home_phone']))  {
			$srchBy="phone";
				try{
					$record_id=addressSearchByPhone();					
					if ($record_id > 0 ) {
						//$message="record was accessed using home phone";
					}
					if (is_array($srchrow)) {						
						$_POST=arrayCopy($srchrow);
					}
					//echo "<Pre>";print_r($srchrow);	echo "</Pre>";
				} catch (Exception $e)  {
						die(pg_last_error());
				}
		}
	
	
		if (isset($_POST['contact_email'])  and !empty($_POST['contact_email'])  ) {
				$srchBy="email";
				//print_r($_POST);			
						try	{
						if ($record_id <= 0 ) {
							$record_id=addressSearchByEmail();
							//$message="record was accessed using email address";
							if (is_array($srchrow)) {
								$_POST=arrayCopy($srchrow);
								//$_POST['other_phone1']='other_phone1';
							}
						}
						//var_dump($srchBy);
						} catch (Exception $e)  {
								die(pg_last_error());
						}
		}
	
			if  ( empty($_POST['home_phone']) and empty($_POST['contact_email']) ) {
					$message="Phone or Contact Email required for carrying out Search";
			}
			else
				if (empty($srchrow['address_id']))
					$message='Address record does not exist';						
	}

}			
if (isset($_POST['submit'])) {
	if ($_POST['submit'] == 'AddNew'){			
			$heading="Add New Record";	
			if ($message='validation passed')
				addressInsert();
			//clear fields
			$message="";
			foreach ($srchrow as $key => $v) {
				$srchrow[$key]="";
				$_POST[$key]="";
			}			
	}
	if ($_POST['submit'] == 'Validate'){
		$heading="Validate";		
		$srchrow=arrayCopy($_POST);
		unset($srchrow['submit']);
		$message = validateFields();
		//var_dump($message);
	}

	if ($_POST['submit'] == 'Edit'){
		$heading="Edit";		
		$message="Edit the record";
		$srchrow=arrayCopy($_POST);
		}

	if ($_POST['submit'] == 'Delete'){		
		$heading="Delete";
		$message="";
		addressDelete();
		}

	
	if ($_POST['submit'] == 'Clear'){
			//$message="";			
			$heading="Address Book";
			//print_r($_POST);
			foreach ($_POST as $key => $v) {
				$srchrow[$key]="";
				$_POST[$key]="";							
			}			
			//echo "<Pre>" ;echo "srchrow:";var_dump($srchrow);print_r($srchrow) ;echo "</Pre>";	
		}
}
?>
<html>
<head>
	<title>Address Book </title> 
	
	<link href="/css/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<link href="css/addressbook.css" rel="stylesheet">
	<script src="/js/jquery-1.9.1.js"></script>
	<script src="/js/jquery-1.9.1.js"></script>
	<script src="/js/jquery-ui-1.10.3.custom.js"></script>	
	<script>	
<!--
	$(function() {
		$( "#birth_date" ).datepicker({
			changeMonth:true,
			changeYear:true,
			minDate: (new Date(1900,1,-1,1) ),
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true
		});	

		$( "#birth_date" ).change(function() {
			$( "#birth_date" ).datepicker( "option", "dateFormat", "yy-mm-dd");			
		});	
	});
	
	-->
</script>

<script>
	<?php
	$availState=array();
	foreach ($citystate as   $key=>$value) 	{ 	
	    array_push($availState,ucfirst(strtolower($value)));
	}	
		sort($availState);		
		$availState=array_unique($availState);		

	
	 echo "$(function() {";
	 echo "var availableCities=["; 
	 foreach ($citystate as   $key=>$value) 	{ 
		echo "\"". $key . "\","; }
	 echo "\"\"];";
	 echo "$(\"#city\").autocomplete({source: availableCities});});";
	 ?>
</script> 
<script>
	<?php
	 echo "$(function() {";
	 echo "var availableStates=["; 
	 foreach ($availState as   $value) 	{ 
		echo "\"". $value . "\","; }
	 echo "\"\"];";
	 echo "$(\"#state\").autocomplete({source: availableStates});});";
	 ?>
</script> 


<script>
	<?php
	 echo "$(function() {";
	 echo "var avlCountries=["; 
	 foreach ($countries  as   $value) 	{ 
		echo "\"". $value . "\","; }
	 echo "\"\"];";
	 echo "$(\"#country\").autocomplete({source: avlCountries});});";
	 ?>
</script> 

</head>

<body>
<?php include_once("analyticstracking.php") ?>
<div  class="headerPanel" >
<address align=center>
Madhavan V.B
19/10 Muthiah 2nd street
Royapettah
Phone:91-44-28476860
Phone:91-9952969398
</address>
<address align=center>
Email:vbmadhavan@gmail.com
</address>
<h1 align=center><?php echo $heading;?></h1>
</div>
<p>
</p>
<!--div class="hpanel">
<h1 align=center>ABC - Address Book on the web </h1>
<p align=center><img src="images/boy.jpg" width=43 height=59> </img></p>
</div-->
<?php echo "<h2 align=center> $heading </h2>"?>


<div  class="leftPanel">
<br/>
<br/>
<!--a href="http://localhost:8080/address_book/main/index.php">Financial Accounting</a><br/>
<a href="http://localhost:8080/address_book/main/index.php">Payroll</a><br/>
<a href="http://localhost:8080/address_book/main/index.php">Inventory</a><br/-->
</div>

<div class=menuPanel>
<p>
<!--a href="http://localhost:8080/" >Home&nbsp;</a-->
<!--a href="http://localhost:8080/address_book/main/index.php" >Contact&nbsp;</a-->
<a href="http://localhost:8080/index.php">Back</a>
</p>
</div>


<form   name="addressbook"  method="POST" action = "<?php  basename(__FILE__); ?>">
<div class=middlePanel>
<!--table  border="1"  width =100%--> 
<!--tr>
<td width=55% align=left>
<br-->
<p>

<?php 
$salutation=array(
//'AirMarshal'=>'AirMarshal','Bishop'=>'Bishop','Captain'=>'Captain',
//'Chakravarthi'=>'Chakravarthi','Consul'=>'Consul','Councillor'=>'Councillor','Colonel'=>'Colonel',
//'Commander'=>'Commander','Commisioner'=>'Commisioner','Dr.'=>'Dr.','Dean'=>'Dean',
//'Desai'=>'Desai','Diwan'=>'Diwan','Governor'=>'Governor','HisExcellency'=>'HisExcellency',
//'HisGrace'=>'HisGrace','HisHighness'=>'HisHighness','HisMajesty'=>'HisMajesty','Justice'=>'Justice',
//'King'=>'King','Lady'=>'Lady','Lord'=>'Lord',
'Miss'=>'Miss','Mr'=>'Mr','Mrs'=>'Mrs','Madam'=>'Madam','Ms'=>'Ms','Major'=>'Major','Master'=>'Master'
//'Sri'=>'Sri','Smt'=>'Smt','Swami'=>'Swami',
//,'Monseur'=>'Monseur','Professor'=>'Professor',
//'Saint'=>'Saint','Sergeant'=>'Sergeant','Shah'=>'Shah',
//'Sir'=>'Sir'
);

echo "Salutation: ";
?>
<script>
<?php
	 echo "$(function() {";
	 echo "var avlSalutes=["; 
	 foreach ($salutation as $key=>$value) 	{ 
		echo "\"". $key . "\","; }
	 echo "\"\"];";
	 echo "$(\"#salutation\").autocomplete({source: avlSalutes});});";
?>
</script>
<input id='salutation' size=10 name='salutation' type='text' value="<?php echo $srchrow['salutation']?>">
<br>	

First Name:&nbsp;<input id="first_name" size=30 name="first_name" type='text'  value="<?php echo $srchrow['first_name'];?>"><br>
Last Name:&nbsp;<input id="last_name" size=30	name="last_name" type='text' value="<?php  echo $srchrow['last_name'] ?>"  ><br>	

Middle Name:&nbsp;<input id="middle_name" size=30 name="middle_name" type='text'  value="<?php echo empty($srchrow['middle_name'])?'':$srchrow['middle_name'];?>"><br>
</p>
<p>
Address1:	<input name="address_1" type='text' size=30 value="<?php  echo $srchrow['address_1'] ?>"><br>	  
Address2:	<input name="address_2" type='text' size=30 value="<?php echo $srchrow['address_2'] ?>"><br>	  
Address3:	<input name="address_3" type='text' size=30 value="<?php echo $srchrow['address_3'] ?>"><br>	  

City:
<input id="city" name="city"  value="<?php echo $srchrow['city']; ?>"><br/>
State:
<input id="state" name="state" type='text'  value="<?php echo $srchrow['state']; ?>"><br>	  
Postal Code:	
<input name="postal_code" type='text' value="<?php echo $srchrow['postal_code'] ?>"><br>	  
Country:&nbsp;
<input id="country" type="text"  name="country" value="<?php echo $srchrow['country'];?>" ><br>
<!--/td-->
<!--td width=50% align=left-->
</div>
<div class=rightPanel>
<p>
Date Of Birth: <input id="birth_date" name=birth_date type='text' value ="<?php echo $srchrow['birth_date'];?>"><br/>
Home Phone&nbsp;:
<input name="home_phone" type='text' value="<?php echo $srchrow['home_phone'] ?>"><br>	  
Work Phone&nbsp;:
<input name="work_phone" type='text' value="<?php echo $srchrow['work_phone'] ?>"><br>	  

Contact Email&nbsp;:
<!--?php $srchrow['contact_email']= isset($srchrow['contact_email'])?$srchrow['contact_email']:"" ;?-->
<input name="contact_email" type='text' value="<?php echo $srchrow['contact_email'] ?>"><br>

Other Phone 1 &nbsp;:	<input name="other_phone1" type='text' value="<?php echo $srchrow['other_phone1'] ;?>">
<?php
$phonetypes=array('LANDLINE'=>'LANDLINE','MOBILE'=>'MOBILE');

?>
<!--input name="other_phone_type1" type='text' value="<?php echo $srchrow['other_phone_type1'] ;?>"><br /--->
<select id='other_phone_type1' name='other_phone_type1' type='text' >";
<?php 
global $srchrow;
foreach ($phonetypes as $key=>$value) {
       if ($srchrow['other_phone_type1']==$value){
		echo "<option name=$key selected>  $value  </option>";
	   } else {
	   echo "<option name=$key >  $value  </option>";
	   }
	   }
?>
</select> <br/>
&nbsp;Other Phone 2 &nbsp;:	<input name="other_phone2" type='text' value="<?php echo $srchrow['other_phone2'] ;?>">
<select id='other_phone_type2' name='other_phone_type2' type='text' >";
<?php 
global $srchrow;
foreach ($phonetypes as $key=>$value) {
       if ($srchrow['other_phone_type2']==$value){
		echo "<option name=$key selected>  $value  </option>";
	   } else {
	   echo "<option name=$key >  $value  </option>";
	   }
	   }
?>
</select> <br/>
&nbsp;Other Phone 3 &nbsp;:	<input name="other_phone3" type='text' value="<?php echo $srchrow['other_phone3'] ;?>">
<select id='other_phone_type3' name='other_phone_type3' type='text' >";
<?php 
global $srchrow;
foreach ($phonetypes as $key=>$value) {
       if ($srchrow['other_phone_type3']==$value){
		echo "<option name=$key selected>  $value  </option>";
	   } else {
	   echo "<option name=$key >  $value  </option>";
	   }
	   }
?>
</select> 
Comments:<br/>
<TEXTAREA ID="comments" NAME="comments"  ROWS=6  COLS=60> 
<?php echo $srchrow["comments"]; ?>
</TEXTAREA

<!--/tr-->
<!--tr>
	<td-->
	<?php 
	if ($message=='validation passed')	{
		$disable=false;
	}
	else if ($message=="cancelled") {
		$disable=false;
	}
	else if ($message=="Updated"){
		$disable=false;
	}
	else if ($message=="Inserted"){
		$disable=false;
	}
	else if ($message=="Deleted"){
		$disable=false;
	}
	else
		$disable=true;	
	?>
</p>	
</div>	
<div class=footerPanel align=center>
	<!--br/-->
	<input id='submit' type='submit' name='submit'  <?php echo ($disable)?'disabled':'';?> value="AddNew" >
	<input id='submit' type='submit' name='submit'  <?php echo ($disable)?'disabled':'';?> value="Save">
	<input id='submit' type='submit' name='submit'  <?php echo ($disable)?'disabled':'';?> value="SaveByEmail" size=10>
	<input id='submit' type='submit' name='submit'  value='Search' >
	<input id='submit' type='submit' name='submit'  value='Clear' >
	<input id='submit' type='submit' name='submit'  <?php echo ($disable)?'disabled':'';?> value='Delete'>
	<input id='submit' type='submit' name='submit'  <?php echo ($disable)?'disabled':'';?> value='Edit'>
	
	<input id='submit' type='submit' name='submit'  value='Validate'>
	<!--/td-->
	<!--td-->
</div>
<div class=errorPanel>
	<?php global $message;echo $message; ?>
</div>	
	<!--/td-->
<!--/tr-->
<!--/tr-->
<!--/table-->



<div  class="logoPanel" align=center>
<img src="/images/gravatar.JPG" width=60 height=60  ></img>
</div>

</form>
<!--p align=center ><?php serverInfo();?></p-->
</body>
</html>



