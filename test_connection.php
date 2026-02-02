<?php
/**
 * データベース接続テスト
 * このファイルは本番環境では削除してください
 */

echo "<h1>モーキス公式サイト - データベース接続テスト</h1>";
echo "<hr>";

// config.phpを読み込み
require_once __DIR__ . '/includes/config.php';

echo "<h2>1. 設定確認</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>項目</th><th>値</th></tr>";
echo "<tr><td>DB_HOST</td><td>" . DB_HOST . "</td></tr>";
echo "<tr><td>DB_NAME</td><td>" . DB_NAME . "</td></tr>";
echo "<tr><td>DB_USER</td><td>" . DB_USER . "</td></tr>";
echo "<tr><td>DB_PASS</td><td>" . str_repeat('*', strlen(DB_PASS)) . " (長さ: " . strlen(DB_PASS) . "文字)</td></tr>";
echo "<tr><td>DB_CHARSET</td><td>" . DB_CHARSET . "</td></tr>";
echo "</table>";

echo "<h2>2. データベース接続テスト</h2>";

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    echo "接続試行中...<br>";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    echo "<p style='color: green; font-weight: bold;'>✓ データベース接続成功！</p>";

    // テーブル一覧を取得
    echo "<h2>3. テーブル確認</h2>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "<p style='color: orange; font-weight: bold;'>⚠ テーブルが存在しません。database.sqlをインポートしてください。</p>";
    } else {
        echo "<p style='color: green;'>✓ " . count($tables) . "個のテーブルが見つかりました:</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table) . "</li>";
        }
        echo "</ul>";

        // 各テーブルのレコード数を確認
        echo "<h2>4. データ確認</h2>";
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr><th>テーブル名</th><th>レコード数</th></tr>";

        foreach ($tables as $table) {
            $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "<tr><td>$table</td><td>$count</td></tr>";
        }
        echo "</table>";

        // usersテーブルの確認
        if (in_array('users', $tables)) {
            echo "<h2>5. 管理者アカウント確認</h2>";
            $users = $pdo->query("SELECT id, username, created_at FROM users")->fetchAll();
            if (!empty($users)) {
                echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
                echo "<tr><th>ID</th><th>ユーザー名</th><th>作成日時</th></tr>";
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . $user['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                    echo "<td>" . $user['created_at'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<p style='color: green;'>✓ デフォルト管理者: admin / admin123</p>";
            }
        }

        // charactersテーブルの確認
        if (in_array('characters', $tables)) {
            echo "<h2>6. キャラクター確認</h2>";
            $chars = $pdo->query("SELECT id, name, name_kana, grade FROM characters ORDER BY sort_order")->fetchAll();
            if (!empty($chars)) {
                echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
                echo "<tr><th>ID</th><th>名前</th><th>ふりがな</th><th>学年</th></tr>";
                foreach ($chars as $char) {
                    echo "<tr>";
                    echo "<td>" . $char['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($char['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($char['name_kana']) . "</td>";
                    echo "<td>" . htmlspecialchars($char['grade']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
    }

    echo "<h2>✅ テスト結果</h2>";
    echo "<div style='background: #d4edda; padding: 20px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo "<p style='color: #155724; font-weight: bold; font-size: 18px;'>すべてのテストが成功しました！</p>";
    echo "<p>次のステップ:</p>";
    echo "<ol>";
    echo "<li><a href='index.html'>公開サイトを表示</a></li>";
    echo "<li><a href='admin/'>管理画面にログイン</a> (admin / admin123)</li>";
    echo "<li><strong>このファイル(test_connection.php)を削除してください</strong></li>";
    echo "</ol>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>✗ データベース接続エラー</p>";
    echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "<p><strong>エラーメッセージ:</strong></p>";
    echo "<pre style='color: #721c24;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "</div>";

    echo "<h3>対処方法:</h3>";
    echo "<ol>";
    echo "<li><strong>データベースが存在するか確認</strong><br>phpMyAdminで「" . DB_NAME . "」が作成されているか確認</li>";
    echo "<li><strong>ユーザー名とパスワードを確認</strong><br>ユーザー名: " . DB_USER . "<br>パスワードが正しいか確認</li>";
    echo "<li><strong>権限を確認</strong><br>ユーザーがデータベースにアクセスできる権限があるか確認</li>";
    echo "<li><strong>ホスト名を確認</strong><br>localhostで接続できない場合、お名前.comの管理画面でホスト名を確認</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p style='color: #999; font-size: 12px;'>モーキス公式サイト データベース接続テスト</p>";
?>
