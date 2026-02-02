<?php
/**
 * 管理画面 - ユーザー管理
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
    $currentUser = $auth->getCurrentUser();

    if ($id === $currentUser['id']) {
        $message = '自分自身のアカウントは削除できません';
        $messageType = 'error';
    } else {
        if ($db->execute("DELETE FROM users WHERE id = ?", [$id])) {
            $message = 'ユーザーを削除しました';
            $messageType = 'success';
        }
    }
}

// 追加・編集処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (empty($username)) {
        $message = 'ユーザー名を入力してください';
        $messageType = 'error';
    } elseif (!$id && empty($password)) {
        $message = 'パスワードを入力してください';
        $messageType = 'error';
    } elseif (!empty($password) && $password !== $passwordConfirm) {
        $message = 'パスワードが一致しません';
        $messageType = 'error';
    } else {
        if ($id) {
            // 編集
            if (!empty($password)) {
                // パスワード変更あり
                $hashedPassword = Auth::hashPassword($password);
                $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
                $result = $db->execute($sql, [$username, $hashedPassword, $id]);
            } else {
                // パスワード変更なし
                $sql = "UPDATE users SET username = ? WHERE id = ?";
                $result = $db->execute($sql, [$username, $id]);
            }

            if ($result) {
                $message = 'ユーザー情報を更新しました';
                $messageType = 'success';
            } else {
                $message = 'ユーザー名が既に使用されています';
                $messageType = 'error';
            }
        } else {
            // 新規追加
            $hashedPassword = Auth::hashPassword($password);
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            if ($db->execute($sql, [$username, $hashedPassword])) {
                $message = 'ユーザーを追加しました';
                $messageType = 'success';
            } else {
                $message = 'ユーザー名が既に使用されています';
                $messageType = 'error';
            }
        }
    }
}

// 編集対象取得
$editUser = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editUser = $db->queryOne("SELECT * FROM users WHERE id = ?", [$editId]);
}

// 一覧取得
$users = $db->query("SELECT * FROM users ORDER BY created_at DESC");
$currentUser = $auth->getCurrentUser();

adminHeader('ユーザー管理');
?>

<h1>ユーザー管理</h1>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<h2><?php echo $editUser ? 'ユーザーを編集' : '新しいユーザーを追加'; ?></h2>

<form method="POST">
    <?php if ($editUser): ?>
        <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
    <?php endif; ?>

    <label>ユーザー名 <span style="color: red;">*</span></label>
    <input type="text" name="username" value="<?php echo $editUser ? h($editUser['username']) : ''; ?>" required>

    <label>パスワード <?php echo $editUser ? '(変更する場合のみ入力)' : '<span style="color: red;">*</span>'; ?></label>
    <input type="password" name="password" <?php echo $editUser ? '' : 'required'; ?>>

    <label>パスワード確認 <?php echo $editUser ? '(変更する場合のみ入力)' : '<span style="color: red;">*</span>'; ?></label>
    <input type="password" name="password_confirm" <?php echo $editUser ? '' : 'required'; ?>>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn"><?php echo $editUser ? '更新' : '追加'; ?></button>
        <?php if ($editUser): ?>
            <a href="users.php" class="btn" style="background: #95a5a6; margin-left: 10px;">キャンセル</a>
        <?php endif; ?>
    </div>
</form>

<h2>ユーザー一覧</h2>

<table>
    <thead>
        <tr>
            <th>ユーザー名</th>
            <th style="width: 200px;">作成日時</th>
            <th style="width: 150px;">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <?php echo h($user['username']); ?>
                    <?php if ($user['id'] === $currentUser['id']): ?>
                        <span style="background: #6c5ce7; color: white; padding: 2px 8px; border-radius: 3px; font-size: 0.8em; margin-left: 5px;">現在のユーザー</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></td>
                <td>
                    <a href="?edit=<?php echo $user['id']; ?>" class="btn" style="font-size: 12px; padding: 5px 10px;">編集</a>
                    <?php if ($user['id'] !== $currentUser['id']): ?>
                        <a href="?delete=<?php echo $user['id']; ?>"
                           class="btn btn-danger"
                           style="font-size: 12px; padding: 5px 10px;"
                           onclick="return confirm('本当に削除しますか?')">削除</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top: 30px; padding: 15px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px;">
    <strong>セキュリティ上の注意:</strong>
    <ul style="margin: 10px 0 0 20px; line-height: 1.8;">
        <li>デフォルトの管理者アカウント（admin/admin123）は必ず変更してください</li>
        <li>パスワードは8文字以上を推奨します</li>
        <li>推測されにくい強固なパスワードを設定してください</li>
    </ul>
</div>

<?php adminFooter(); ?>
