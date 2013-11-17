<?php
function AuthSearchByUser() {
global $Authrow;
try {
	$conn= pg_connect("host=localhost port=5432 dbname=address_book user=adduser password=adduser") 
		or die("connection failed");
} catch (Exception $e) { 
	echo "Exception code is" . " ". $e->getCode() . "Error is " . pg_last_error() ;
}
$db=getConnection();
$authsql=<<<AUTHSRCHSQL
select username,password
from
Credentials 
where 
username = $1
and status <> 'D'
AUTHSRCHSQL;

$params = array();
$params[0]=  $_POST['username'];

$stmtname="AUTHSRCHSQL";
$prepsrch = pg_prepare($db, $stmtname, $phonesql ) or die(pg_last_error());
global $srch;	
$srch = pg_execute($db, $stmtname, $params) or die(pg_last_error());;
$Authrow = pg_fetch_assoc($srch) ;
//print_r($Authrow);
$deallocsql = sprintf('DEALLOCATE "%s"', pg_escape_string($stmtname) );
if(!pg_query($deallocsql)) {
	die("Can't query '$deallocsql': " . pg_last_error());
} 
$recordId=$srchrow['AuthId'];
return $recordId;
} //addressSearchByPhone
?>
