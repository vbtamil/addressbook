<!DOCTYPE html>
<?php
function AuthSearchByUser($user,$pswd) {
global $Authrow,$pwd,$status;
if (empty($user)) {
	return false;
}
if (empty($pswd)) {
	return false;
}

try {
	$db= pg_connect("host=localhost port=5432 dbname=address_book user=adduser password=adduser") 
		or die("connection failed");
} catch (Exception $e) { 
	echo "Exception code is" . " ". $e->getCode() . "Error is " . pg_last_error() ;
}
$Authrow['username']=$user;
//print "<br/>dollar db:";
//var_dump($db);
$authsql=<<<AUTHSRCHSQL
select username,password from Credentials where  username = $1 
AUTHSRCHSQL;
//print "<br/>dollar authsql:";
//var_dump($authsql);
$params = array();
$params[0]=  isset($Authrow['username'])?$Authrow['username']:$_POST['username'];

$stmtname="AUTHSRCHSQL";
//print "<br/>dollar stmtname:";
//var_dump($stmtname);
$prepsrch = pg_prepare($db, $stmtname, $authsql ) or die(pg_last_error());
//print "<br/>dollar prepsrch:";
//var_dump($prepsrch);
$srch = pg_execute($db, $stmtname, $params) or die(pg_last_error());
//print "<br/>dollar srch:";
//var_dump($srch);
//print "<br/>Authrow [usrname]:(before fetchassoc)";
//var_dump($Authrow['username']);
//print "<br/>Authrow [password]:(before fetchassoc)";
//var_dump($Authrow['password']);
$Authrow = pg_fetch_assoc($srch) or die("error occurred pg_fetch_assoc" .$srch.pg_last_error());
//print "<br/>Authrow['username']:(after fetchassoc)";
//var_dump($Authrow['username']);
//print "<br/>Authrow['password']:(after fetchassoc)";
//var_dump($Authrow['password']);
//print_r($Authrow);
$deallocsql = sprintf('DEALLOCATE "%s"', pg_escape_string($stmtname));
if(!pg_query($deallocsql)) {
	die("Can't deallocate query " . $deallocsql . ": " . pg_last_error());
} 

$pwd=$Authrow['password'];
if ($pswd==convert_uuencode($pwd))
return true;
else
return false;


//print "<br/> db returned pwd:";
//var_dump($pwd);
return $pwd;
} //function

//main

if (isset ($_POST['submit']))  {
	$Authrow['username']=$_POST['username'];
	$Authrow['password']=$_POST['password'];
	$encoded_pass=convert_uuencode($_POST['password']);
	$status=AuthSearchByUser($_POST['username'],$encoded_pass);
	 $_SESSION['status'] = $status;

	if ($status) {
		$_SESSION['status']=$status;
		echo "<h1 align=center>Authentication Successful</h1>";
		}
	else {
		echo "<h1 align=center>Access Denied</h1>";
		die();
		}	
}
else {
		$Authrow['username']=isset($_POST['username'])?$_POST['username']:"";
		$Authrow['password']=isset($_POST['password'])?$_POST['password']:"";
	}
	//
?>
<!-- allow connection to index page -->
<?php if ($status) { ?>
		<br/>
		<p align =center>
		<A href="http://localhost:8080/index.php"> Enter </A>
		</p>
<?php } ?>

