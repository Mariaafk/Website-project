<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// remove all session variables
session_unset();

setcookie('PHPSESSID', '', -1, '/'); 
// destroy the session
session_destroy();

header("Location: index.html");
?>
 
</body>
</html>