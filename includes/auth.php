<?php
/**
 * モーキス公式サイト - 認証処理
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->startSession();
    }

    // セッション開始
    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }
    }

    // ログイン処理
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $user = $this->db->queryOne($sql, [$username]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['login_time'] = time();
            return true;
        }
        return false;
    }

    // ログアウト処理
    public function logout() {
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }

    // ログイン状態チェック
    public function isLoggedIn() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['login_time'])) {
            return false;
        }

        // セッションタイムアウトチェック
        if (time() - $_SESSION['login_time'] > SESSION_LIFETIME) {
            $this->logout();
            return false;
        }

        // セッション時間更新
        $_SESSION['login_time'] = time();
        return true;
    }

    // ログインが必要なページの保護
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: /admin/index.php');
            exit;
        }
    }

    // 現在のユーザー情報取得
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            $sql = "SELECT id, username, created_at FROM users WHERE id = ?";
            return $this->db->queryOne($sql, [$_SESSION['user_id']]);
        }
        return null;
    }

    // パスワードハッシュ生成（ユーザー管理用）
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
