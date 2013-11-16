<!DOCTYPE html>
<?php
ob_start();
session_start();
$_SESSION['current']="Inactive";
?>

<html>
<head>
<title></title>
</head>
<link href="css/index.css" rel="stylesheet">
<script>
</script>

<body>
<?php include_once("analyticstracking.php") ?>
<form method="post" action="authenticate.php">

<div class="leftPanel">
</div>

<div class="authPanel" align=center>
&nbsp;&nbsp;&nbsp;&nbsp;   Login: <input id=userid name="username" type="text"/><br/><br/>
Password: <input id=pwd name="password" type="password"  /><br/><br/>
<input id=submit type=submit name=submit value="Submit">
</div>
</form>
</body>
</html>



