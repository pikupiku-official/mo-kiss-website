<?php
/**
 * モーキス公式サイト - 共通関数
 */

/**
 * HTMLエスケープ
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * リダイレクト
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * 日付フォーマット（YYYY.MM.DD形式）
 */
function formatDate($date) {
    return date('Y.m.d', strtotime($date));
}

/**
 * 日付フォーマット（YYYY年MM月DD日形式）
 */
function formatDateJp($date) {
    return date('Y年n月j日', strtotime($date));
}

/**
 * アクセスカウンターの値を取得・更新
 */
function getAccessCount($increment = false) {
    $db = Database::getInstance();

    if ($increment) {
        $db->execute("UPDATE access_counter SET count = count + 1 WHERE id = 1");
    }

    $result = $db->queryOne("SELECT count FROM access_counter WHERE id = 1");
    return $result ? $result['count'] : 0;
}

/**
 * カウンターの数字を画像形式で出力（レトロ風）
 */
function displayCounter($count) {
    $digits = str_pad($count, 6, '0', STR_PAD_LEFT);
    $html = '<span class="counter">';
    for ($i = 0; $i < strlen($digits); $i++) {
        $html .= '<img src="/api/counter.php?digit=' . $digits[$i] . '" alt="' . $digits[$i] . '" width="20" height="25">';
    }
    $html .= '</span>';
    return $html;
}

/**
 * BGM設定取得
 */
function getBgmSetting() {
    $db = Database::getInstance();
    $enabled = $db->queryOne("SELECT setting_value FROM settings WHERE setting_key = 'bgm_enabled'");
    $file = $db->queryOne("SELECT setting_value FROM settings WHERE setting_key = 'bgm_file'");

    return [
        'enabled' => $enabled ? $enabled['setting_value'] : '1',
        'file' => $file ? $file['setting_value'] : 'sounds/bgm/main.mp3'
    ];
}

/**
 * What's Newsを取得
 */
function getNews($limit = 10) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM news ORDER BY date DESC, id DESC LIMIT ?";
    return $db->query($sql, [$limit]);
}

/**
 * ページコンテンツ取得
 */
function getPageContent($slug) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM pages WHERE slug = ?";
    return $db->queryOne($sql, [$slug]);
}

/**
 * キャラクター一覧取得
 */
function getCharacters() {
    $db = Database::getInstance();
    $sql = "SELECT * FROM characters ORDER BY sort_order ASC";
    return $db->query($sql);
}

/**
 * SPECIAL情報取得
 */
function getSpecialInfo() {
    $db = Database::getInstance();
    $sql = "SELECT * FROM special WHERE id = 1";
    return $db->queryOne($sql);
}

/**
 * カウントダウン計算（発売日までの日数）
 */
function getCountdown($releaseDate) {
    if (!$releaseDate) return null;

    $now = new DateTime();
    $release = new DateTime($releaseDate);
    $diff = $now->diff($release);

    if ($diff->invert) {
        return '発売済み';
    }

    return $diff->days . '日';
}

/**
 * ファイルアップロード処理
 */
function uploadFile($file, $targetDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'ファイルのアップロードに失敗しました'];
    }

    // ファイルサイズチェック
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'message' => 'ファイルサイズが大きすぎます'];
    }

    // 拡張子チェック
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'ogg'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts)) {
        return ['success' => false, 'message' => '許可されていないファイル形式です'];
    }

    // ファイル名生成（重複回避）
    $filename = uniqid() . '_' . basename($file['name']);
    $targetPath = $targetDir . $filename;

    // ディレクトリ作成
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // ファイル移動
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $targetPath, 'filename' => $filename];
    }

    return ['success' => false, 'message' => 'ファイルの保存に失敗しました'];
}

/**
 * 管理画面の共通ヘッダー出力
 */
function adminHeader($title = '') {
    $pageTitle = $title ? $title . ' - ' : '';
    $pageTitle .= 'モーキス 管理画面';
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo h($pageTitle); ?></title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
                background: #f5f5f5;
                padding: 20px;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #333;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 3px solid #6c5ce7;
            }
            h2 {
                color: #555;
                margin: 30px 0 15px;
                font-size: 1.4em;
            }
            nav {
                background: #6c5ce7;
                padding: 15px 0;
                margin: -30px -30px 30px -30px;
                border-radius: 8px 8px 0 0;
            }
            nav a {
                color: white;
                text-decoration: none;
                padding: 10px 15px;
                margin: 0 5px;
                display: inline-block;
                border-radius: 4px;
                transition: background 0.3s;
            }
            nav a:hover {
                background: rgba(255,255,255,0.2);
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #6c5ce7;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                border: none;
                cursor: pointer;
                font-size: 14px;
                transition: background 0.3s;
            }
            .btn:hover {
                background: #5f4ed1;
            }
            .btn-danger {
                background: #e74c3c;
            }
            .btn-danger:hover {
                background: #c0392b;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            table th, table td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            table th {
                background: #f8f9fa;
                font-weight: bold;
            }
            form label {
                display: block;
                margin: 15px 0 5px;
                font-weight: bold;
                color: #555;
            }
            form input[type="text"],
            form input[type="password"],
            form input[type="date"],
            form input[type="number"],
            form textarea,
            form select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 14px;
            }
            form textarea {
                min-height: 200px;
                font-family: monospace;
            }
            .message {
                padding: 15px;
                margin: 20px 0;
                border-radius: 4px;
            }
            .message.success {
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .message.error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <nav>
                <a href="/admin/dashboard.php">ダッシュボード</a>
                <a href="/admin/news.php">What's New</a>
                <a href="/admin/story.php">STORY</a>
                <a href="/admin/characters.php">キャラクター</a>
                <a href="/admin/system.php">SYSTEM</a>
                <a href="/admin/special.php">SPECIAL</a>
                <a href="/admin/bgm.php">BGM</a>
                <a href="/admin/users.php">ユーザー</a>
                <a href="/admin/logout.php" style="float: right;">ログアウト</a>
            </nav>
    <?php
}

/**
 * 管理画面の共通フッター出力
 */
function adminFooter() {
    ?>
        </div>
    </body>
    </html>
    <?php
}
