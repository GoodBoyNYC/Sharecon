<html>
<title>Logout</title>
<body bgcolor="F9BB00">

<?php
session_start();
session_destroy();
echo "You are logged out. You will be redirected in 1 seconds";
  header("refresh: 1; index.php");
?>
</body>
</html>