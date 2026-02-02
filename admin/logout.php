<?php
/**
 * 管理画面 - ログアウト
 */

require_once __DIR__ . '/../includes/auth.php';

$auth = new Auth();
$auth->logout();

header('Location: index.php');
exit;
