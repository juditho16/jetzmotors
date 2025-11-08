<?php
session_start();
session_unset();
session_destroy();
header("Location: user.php"); // back to login
exit;
