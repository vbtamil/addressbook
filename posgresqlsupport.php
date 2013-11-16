<?php
/*POSTGREsql Support*/
function getConnection(){
try {
	$conn= pg_connect("host=localhost port=5432 dbname=address_book user=adduser password=adduser") 
		or die("connection failed");
} catch (Exception $e) { 
	echo "Exception code is" . " ". $e->getCode() . "Error is " . pg_last_error() ;
}
return $conn;
}
function VersionInfo() {
$db=getConnection();
  $v = pg_version($db);
  echo "<pre>";
  echo 'client '  . $v['client'];echo "<br>";
  echo 'server '   . $v['server'];echo "<br>";
  echo 'protocol ' . $v['protocol'];echo "<br>";
  echo "</pre>";
}
function addressSearchByPhone() {
global $srchrow;

$db=getConnection();
$phonesql=<<<PHONESRCHSQL
select address_id,salutation,last_name,first_name,middle_name,birth_date,address_1,address_2,address_3,
home_phone,work_phone,contact_email,
other_phone1,other_phone2,other_phone3,city,state,country,postal_code,
other_phone_type1,other_phone_type2,other_phone_type3,comments
from
people AS h
where 
home_phone = $1
and record_status <> 'D'
PHONESRCHSQL;

$params = array();
$params[0]=  $_POST['home_phone'];

$stmtname="PHONESRCHSQL";
$prepsrch = pg_prepare($db, $stmtname, $phonesql ) or die(pg_last_error());
global $srch;	
$srch = pg_execute($db, $stmtname, $params) or die(pg_last_error());;
$srchrow = pg_fetch_assoc($srch) ;
//print_r($srchrow);
$deallocsql = sprintf('DEALLOCATE "%s"', pg_escape_string($stmtname) );
if(!pg_query($deallocsql)) {
	die("Can't query '$deallocsql': " . pg_last_error());
} 
$recordId=$srchrow['address_id'];
return $recordId;
} //addressSearchByPhone
function addressSearchByEmail() {
global $srchrow;

$db=getConnection();
$Emailsql=<<<EMAILSRCHSQL
select address_id,salutation,last_name,first_name,middle_name,birth_date,address_1,address_2,address_3,
home_phone,work_phone,contact_email,
other_phone1,other_phone2,other_phone3,city,state,country,postal_code,
other_phone_type1,other_phone_type2,other_phone_type3,comments
from
people AS h
where 
contact_email = $1
and record_status <> 'D'
EMAILSRCHSQL;

$params = array();
$params[0]=  strtolower($_POST['contact_email']);
$stmtname="EMAILSRCHSQL";
$prepsrch = pg_prepare($db, $stmtname, $Emailsql ) or die(pg_last_error());
global $srch;	
$srch = pg_execute($db, $stmtname, $params) or die(pg_last_error());;
$srchrow = pg_fetch_assoc($srch) ;
//print_r($srchrow);
$deallocsql = sprintf('DEALLOCATE "%s"', pg_escape_string($stmtname) );
if(!pg_query($deallocsql)) {
	die("Can't query '$deallocsql': " . pg_last_error());
} 
$recordId=$srchrow['address_id'];
return $recordId;
} //addressSearchByEmail





?>
