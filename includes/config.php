<?php
/**
 * モーキス公式サイト - データベース設定ファイル
 */

// データベース接続情報
define('DB_HOST', 'mysql1016.onamae.ne.jp');
define('DB_NAME', 'c8npo_mksite');
define('DB_USER', 'c8npo_pikupiku');
define('DB_PASS', 'Pkpktheme0_');
define('DB_CHARSET', 'utf8mb4');

// サイト設定
define('SITE_URL', 'https://yourdomain.com');  // 実際のドメインに変更してください
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
