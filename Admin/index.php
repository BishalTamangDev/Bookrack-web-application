<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['bookrack-admin-id'])){
    header("Location: /bookrack/admin/dashboard");
}else{
    header("Location: /bookrack/admin/signin");
}