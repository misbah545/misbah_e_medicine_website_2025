<?php
session_start();
session_unset();
session_destroy();
//header("Location: ../../frontend/pages/admin_login.html");
header("Location: http://localhost:8000/frontend/index.html");

exit();
?>
