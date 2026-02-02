<?php
/**
 * 管理画面 - SYSTEM管理
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance();
$message = '';
$messageType = '';

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';

    if (empty($content)) {
        $message = '内容を入力してください';
        $messageType = 'error';
    } else {
        $existing = $db->queryOne("SELECT * FROM pages WHERE slug = 'system'");

        if ($existing) {
            if ($db->execute("UPDATE pages SET content = ? WHERE slug = 'system'", [$content])) {
                $message = 'SYSTEMページを更新しました';
                $messageType = 'success';
            }
        } else {
            if ($db->execute("INSERT INTO pages (slug, title, content) VALUES ('system', 'SYSTEM', ?)", [$content])) {
                $message = 'SYSTEMページを作成しました';
                $messageType = 'success';
            }
        }
    }
}

// 現在のコンテンツ取得
$page = getPageContent('system');

adminHeader('SYSTEM管理');
?>

<h1>SYSTEM管理</h1>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<form method="POST">
    <label>システム説明</label>
    <p style="color: #666; font-size: 0.9em; margin: 5px 0 10px;">HTMLタグが使用できます。&lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt; など</p>
    <textarea name="content" required><?php echo $page ? h($page['content']) : ''; ?></textarea>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">更新</button>
    </div>
</form>

<h2>プレビュー</h2>
<div style="border: 2px solid #ddd; padding: 20px; background: #fafafa; border-radius: 4px; margin-top: 20px;">
    <?php echo $page ? $page['content'] : '<p style="color: #999;">内容がありません</p>'; ?>
</div>

<?php adminFooter(); ?>
