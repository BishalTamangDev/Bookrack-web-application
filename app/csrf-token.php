<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['csrf_token']))
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

echo $_SESSION['csrf_token'];