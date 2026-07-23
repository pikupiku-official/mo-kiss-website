<?php
/**
 * モーキス公式サイト - データベース設定ファイル
 */

// データベース接続情報
// Keep production credentials in config.local.php (not in Git).
$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    require_once $localConfig;
}

// Environment variables are also supported; local constants take priority.
defined('DB_HOST') || define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
defined('DB_NAME') || define('DB_NAME', getenv('DB_NAME') ?: '');
defined('DB_USER') || define('DB_USER', getenv('DB_USER') ?: '');
defined('DB_PASS') || define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// サイト設定
defined('SITE_URL') || define('SITE_URL', getenv('SITE_URL') ?: 'https://mokiss.jp');
define('SITE_TITLE', 'モーキス 公式サイト');

// セッション設定
define('SESSION_NAME', 'mokiss_admin');
define('SESSION_LIFETIME', 3600); // 1時間

// アップロード設定
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// エラー表示設定（本番環境では必ず無効化）
ini_set('display_errors', 0);
error_reporting(0);
