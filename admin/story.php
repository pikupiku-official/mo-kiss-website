<?php
/**
 * 管理画面 - STORY管理
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
        $existing = $db->queryOne("SELECT * FROM pages WHERE slug = 'story'");

        if ($existing) {
            if ($db->execute("UPDATE pages SET content = ? WHERE slug = 'story'", [$content])) {
                $message = 'STORYページを更新しました';
                $messageType = 'success';
            }
        } else {
            if ($db->execute("INSERT INTO pages (slug, title, content) VALUES ('story', 'STORY', ?)", [$content])) {
                $message = 'STORYページを作成しました';
                $messageType = 'success';
            }
        }
    }
}

// 現在のコンテンツ取得
$page = getPageContent('story');

adminHeader('STORY管理');
?>

<h1>STORY管理</h1>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<form method="POST">
    <label>ストーリー内容</label>
    <p style="color: #666; font-size: 0.9em; margin: 5px 0 10px;">HTMLタグが使用できます。&lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt; など</p>
    <textarea name="content" required><?php echo $page ? h($page['content']) : ''; ?></textarea>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">更新</button>
    </div>
</form>

<h2>プレビュー</h2>
<div style="border: 2px solid #ddd; padding: 20px; background: #fafafa; border-radius: 4px; margin-top: 20px;">
    <?php echo $page ? $page['content'] : '<p style="color: #999;">内容がありません</p>'; ?>
</div>

<div style="margin-top: 30px; padding: 15px; background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 6px;">
    <strong>使用例:</strong>
    <pre style="background: white; padding: 10px; border-radius: 4px; margin-top: 10px; overflow-x: auto;">&lt;h2&gt;物語&lt;/h2&gt;
&lt;p&gt;1999年7月、東京都国分寺市――&lt;/p&gt;
&lt;p&gt;ノストラダムスの大予言が現実のものとなり、世界は終末へのカウントダウンを始めた。&lt;/p&gt;</pre>
</div>

<?php adminFooter(); ?>
