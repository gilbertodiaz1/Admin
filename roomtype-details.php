<?php
include("includes/db.conn.php");
include('includes/conf.class.php');

$row=mysql_fetch_assoc(mysql_query("SELECT * FROM `bsi_roomtype` where roomtype_ID=".$bsiCore->ClearInput($_GET['tid'])));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Room Type Details</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif;">
<span style="width:15px; font-weight:bold;"><?php echo $row['type_name']; ?></span><hr />
<pre>
<?php echo htmlspecialchars($row['description']); ?>
</pre>
</body>
</html>
