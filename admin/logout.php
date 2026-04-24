<?php
require_once __DIR__ . '/../config/config.php';
session_destroy(); session_start();
header('Location: ' . SITE_URL . '/admin/login.php');
exit;
