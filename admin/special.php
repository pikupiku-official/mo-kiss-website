<?php
/**
 * 管理画面 - SPECIAL管理
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$auth = new Auth();
$auth->requireLogin();

$message = '';
$messageType = '';

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $releaseDate = $_POST['release_date'] ?? null;
    $content = $_POST['content'] ?? '';

    if (empty($content)) {
        $message = '内容を入力してください';
        $messageType = 'error';
    } else {
        if (saveSpecialInfo($releaseDate, $content)) {
            $message = 'SPECIALページを更新しました';
            $messageType = 'success';
        } else {
            $message = '保存に失敗しました。contentディレクトリの書き込み権限を確認してください';
            $messageType = 'error';
        }
    }
}

// 現在のコンテンツ取得
$special = getSpecialInfo();
$countdown = $special && $special['release_date'] ? getCountdown($special['release_date']) : null;

adminHeader('SPECIAL管理');
?>

<h1>SPECIAL管理</h1>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<?php if ($countdown && $countdown !== '発売済み'): ?>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: center;">
        <h2 style="color: white; margin-bottom: 10px;">発売まであと</h2>
        <div style="font-size: 3em; font-weight: bold;"><?php echo h($countdown); ?></div>
    </div>
<?php endif; ?>

<form method="POST">
    <label>発売予定日</label>
    <input type="date" name="release_date" value="<?php echo $special ? h($special['release_date']) : ''; ?>">
    <p style="color: #666; font-size: 0.9em; margin: 5px 0 10px;">設定するとカウントダウンが表示されます</p>

    <label>SPECIAL内容</label>
    <p style="color: #666; font-size: 0.9em; margin: 5px 0 10px;">HTMLタグが使用できます。発売情報、価格、動作環境などを記載してください</p>
    <textarea name="content" required style="min-height: 300px;"><?php echo $special ? h($special['content']) : ''; ?></textarea>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">更新</button>
    </div>
</form>

<h2>プレビュー</h2>
<div style="border: 2px solid #ddd; padding: 20px; background: #fafafa; border-radius: 4px; margin-top: 20px;">
    <?php echo $special ? $special['content'] : '<p style="color: #999;">内容がありません</p>'; ?>
</div>

<?php adminFooter(); ?>
