<?php 
session_start();
unset($_SESSION['user_id']);
header('Location: YOUR_DOMAIN_HERE');

?>