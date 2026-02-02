<?php
/**
 * 管理画面 - What's New管理
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

// 削除処理
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->execute("DELETE FROM news WHERE id = ?", [$id])) {
        $message = 'What\'s Newsを削除しました';
        $messageType = 'success';
    } else {
        $message = '削除に失敗しました';
        $messageType = 'error';
    }
}

// 追加・編集処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $date = $_POST['date'] ?? date('Y-m-d');
    $content = $_POST['content'] ?? '';

    if (empty($content)) {
        $message = '内容を入力してください';
        $messageType = 'error';
    } else {
        if ($id) {
            // 編集
            if ($db->execute("UPDATE news SET date = ?, content = ? WHERE id = ?", [$date, $content, $id])) {
                $message = 'What\'s Newsを更新しました';
                $messageType = 'success';
            }
        } else {
            // 新規追加
            if ($db->execute("INSERT INTO news (date, content) VALUES (?, ?)", [$date, $content])) {
                $message = 'What\'s Newsを追加しました';
                $messageType = 'success';
            }
        }
    }
}

// 編集対象取得
$editNews = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editNews = $db->queryOne("SELECT * FROM news WHERE id = ?", [$editId]);
}

// 一覧取得
$newsList = $db->query("SELECT * FROM news ORDER BY date DESC, id DESC");

adminHeader('What\'s New管理');
?>

<h1>What's New管理</h1>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<h2><?php echo $editNews ? 'What\'s Newsを編集' : '新しいWhat\'s Newsを追加'; ?></h2>

<form method="POST">
    <?php if ($editNews): ?>
        <input type="hidden" name="id" value="<?php echo $editNews['id']; ?>">
    <?php endif; ?>

    <label>日付</label>
    <input type="date" name="date" value="<?php echo $editNews ? h($editNews['date']) : date('Y-m-d'); ?>" required>

    <label>内容</label>
    <textarea name="content" required style="min-height: 100px;"><?php echo $editNews ? h($editNews['content']) : ''; ?></textarea>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn"><?php echo $editNews ? '更新' : '追加'; ?></button>
        <?php if ($editNews): ?>
            <a href="news.php" class="btn" style="background: #95a5a6; margin-left: 10px;">キャンセル</a>
        <?php endif; ?>
    </div>
</form>

<h2>What's News一覧</h2>

<?php if (empty($newsList)): ?>
    <p style="color: #999; padding: 20px; text-align: center;">まだWhat's Newsがありません</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th style="width: 120px;">日付</th>
                <th>内容</th>
                <th style="width: 150px;">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($newsList as $news): ?>
                <tr>
                    <td><?php echo formatDate($news['date']); ?></td>
                    <td><?php echo h($news['content']); ?></td>
                    <td>
                        <a href="?edit=<?php echo $news['id']; ?>" class="btn" style="font-size: 12px; padding: 5px 10px;">編集</a>
                        <a href="?delete=<?php echo $news['id']; ?>"
                           class="btn btn-danger"
                           style="font-size: 12px; padding: 5px 10px;"
                           onclick="return confirm('本当に削除しますか?')">削除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php adminFooter(); ?>
