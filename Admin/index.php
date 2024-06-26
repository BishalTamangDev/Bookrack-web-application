<?php
if (session_status() == PHP_SESSION_NONE) 
    session_start();

if(isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-dashboard");
else
    header("Location: /bookrack/admin/admin-signin");