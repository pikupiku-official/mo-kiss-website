<?php
/**
 * 管理画面 - キャラクター管理
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
    if ($db->execute("DELETE FROM characters WHERE id = ?", [$id])) {
        $message = 'キャラクターを削除しました';
        $messageType = 'success';
    }
}

// 追加・編集処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $name = $_POST['name'] ?? '';
    $nameKana = $_POST['name_kana'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $club = $_POST['club'] ?? '';
    $height = $_POST['height'] ?? '';
    $description = $_POST['description'] ?? '';
    $imagePath = $_POST['image_path'] ?? '';

    if (empty($name)) {
        $message = 'キャラクター名を入力してください';
        $messageType = 'error';
    } else {
        if ($id) {
            // 編集
            $sql = "UPDATE characters SET sort_order = ?, name = ?, name_kana = ?, grade = ?, club = ?, height = ?, description = ?, image_path = ? WHERE id = ?";
            if ($db->execute($sql, [$sortOrder, $name, $nameKana, $grade, $club, $height, $description, $imagePath, $id])) {
                $message = 'キャラクター情報を更新しました';
                $messageType = 'success';
            }
        } else {
            // 新規追加
            $sql = "INSERT INTO characters (sort_order, name, name_kana, grade, club, height, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            if ($db->execute($sql, [$sortOrder, $name, $nameKana, $grade, $club, $height, $description, $imagePath])) {
                $message = 'キャラクターを追加しました';
                $messageType = 'success';
            }
        }
    }
}

// 編集対象取得
$editChar = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editChar = $db->queryOne("SELECT * FROM characters WHERE id = ?", [$editId]);
}

// 一覧取得
$characters = getCharacters();

adminHeader('キャラクター管理');
?>

<h1>キャラクター管理</h1>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<h2><?php echo $editChar ? 'キャラクターを編集' : '新しいキャラクターを追加'; ?></h2>

<form method="POST">
    <?php if ($editChar): ?>
        <input type="hidden" name="id" value="<?php echo $editChar['id']; ?>">
    <?php endif; ?>

    <label>表示順</label>
    <input type="number" name="sort_order" value="<?php echo $editChar ? h($editChar['sort_order']) : '0'; ?>" min="0">

    <label>名前 <span style="color: red;">*</span></label>
    <input type="text" name="name" value="<?php echo $editChar ? h($editChar['name']) : ''; ?>" required>

    <label>ふりがな</label>
    <input type="text" name="name_kana" value="<?php echo $editChar ? h($editChar['name_kana']) : ''; ?>">

    <label>学年</label>
    <input type="text" name="grade" value="<?php echo $editChar ? h($editChar['grade']) : ''; ?>" placeholder="例: 2年">

    <label>部活</label>
    <input type="text" name="club" value="<?php echo $editChar ? h($editChar['club']) : ''; ?>" placeholder="例: 水泳部">

    <label>身長</label>
    <input type="text" name="height" value="<?php echo $editChar ? h($editChar['height']) : ''; ?>" placeholder="例: 174cm">

    <label>紹介文</label>
    <textarea name="description" style="min-height: 150px;"><?php echo $editChar ? h($editChar['description']) : ''; ?></textarea>

    <label>画像パス</label>
    <input type="text" name="image_path" value="<?php echo $editChar ? h($editChar['image_path']) : ''; ?>" placeholder="例: images/characters/kanna.png">
    <p style="color: #666; font-size: 0.9em; margin: 5px 0;">画像は手動でサーバーにアップロードしてください</p>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn"><?php echo $editChar ? '更新' : '追加'; ?></button>
        <?php if ($editChar): ?>
            <a href="characters.php" class="btn" style="background: #95a5a6; margin-left: 10px;">キャンセル</a>
        <?php endif; ?>
    </div>
</form>

<h2>キャラクター一覧</h2>

<?php if (empty($characters)): ?>
    <p style="color: #999; padding: 20px; text-align: center;">まだキャラクターが登録されていません</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;">順序</th>
                <th>名前</th>
                <th>学年</th>
                <th>部活</th>
                <th>身長</th>
                <th style="width: 180px;">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($characters as $char): ?>
                <tr>
                    <td><?php echo h($char['sort_order']); ?></td>
                    <td>
                        <strong><?php echo h($char['name']); ?></strong>
                        <?php if ($char['name_kana']): ?>
                            <br><small style="color: #999;"><?php echo h($char['name_kana']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo h($char['grade']); ?></td>
                    <td><?php echo h($char['club']); ?></td>
                    <td><?php echo h($char['height']); ?></td>
                    <td>
                        <a href="?edit=<?php echo $char['id']; ?>" class="btn" style="font-size: 12px; padding: 5px 10px;">編集</a>
                        <a href="?delete=<?php echo $char['id']; ?>"
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
