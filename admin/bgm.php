<?php
/**
 * 管理画面 - BGM管理
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
    $bgmEnabled = isset($_POST['bgm_enabled']) ? '1' : '0';
    $bgmFile = $_POST['bgm_file'] ?? 'sounds/bgm/main.mp3';

    // BGM有効/無効設定
    $db->execute("INSERT INTO settings (setting_key, setting_value) VALUES ('bgm_enabled', ?) ON DUPLICATE KEY UPDATE setting_value = ?", [$bgmEnabled, $bgmEnabled]);

    // BGMファイル設定
    $db->execute("INSERT INTO settings (setting_key, setting_value) VALUES ('bgm_file', ?) ON DUPLICATE KEY UPDATE setting_value = ?", [$bgmFile, $bgmFile]);

    $message = 'BGM設定を更新しました';
    $messageType = 'success';
}

// 現在の設定取得
$bgmSettings = getBgmSetting();

adminHeader('BGM管理');
?>

<h1>BGM管理</h1>

<?php if ($message): ?>
    <div class="message <?php echo $messageType; ?>">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<form method="POST">
    <label>
        <input type="checkbox" name="bgm_enabled" value="1" <?php echo $bgmSettings['enabled'] ? 'checked' : ''; ?>>
        BGMを有効にする
    </label>

    <label>BGMファイルパス</label>
    <input type="text" name="bgm_file" value="<?php echo h($bgmSettings['file']); ?>" required>
    <p style="color: #666; font-size: 0.9em; margin: 5px 0;">MP3ファイルを推奨。ファイルは手動で sounds/bgm/ ディレクトリにアップロードしてください</p>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">更新</button>
    </div>
</form>

<h2>BGMファイルのアップロード方法</h2>

<div style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin-top: 20px;">
    <ol style="margin-left: 20px; line-height: 1.8;">
        <li>MP3形式の音楽ファイルを準備します</li>
        <li>FTPまたはファイルマネージャーで <code>sounds/bgm/</code> ディレクトリにアップロードします</li>
        <li>上記のフォームで、アップロードしたファイルのパスを指定します<br>
            <small style="color: #666;">例: sounds/bgm/main.mp3</small>
        </li>
        <li>「更新」ボタンを押して保存します</li>
    </ol>
</div>

<h2>注意事項</h2>

<div style="background: #fff3cd; padding: 15px; border: 1px solid #ffc107; border-radius: 6px; margin-top: 20px;">
    <ul style="margin-left: 20px; line-height: 1.8;">
        <li>1999年当時はMIDIファイルが主流でしたが、現代のブラウザでは再生できないためMP3を使用します</li>
        <li>ファイルサイズが大きすぎるとページ読み込みが遅くなるため、3MB以下を推奨します</li>
        <li>一部のブラウザでは自動再生がブロックされる場合があります</li>
        <li>著作権に注意してください。フリー音源または許可を得た楽曲のみ使用してください</li>
    </ul>
</div>

<h2>テスト再生</h2>

<?php if ($bgmSettings['enabled']): ?>
    <div style="background: white; padding: 20px; border: 2px solid #ddd; border-radius: 6px; margin-top: 20px;">
        <audio controls style="width: 100%;">
            <source src="/<?php echo h($bgmSettings['file']); ?>" type="audio/mpeg">
            お使いのブラウザは音声再生に対応していません。
        </audio>
    </div>
<?php else: ?>
    <p style="color: #999; padding: 20px; text-align: center;">BGMが無効になっています</p>
<?php endif; ?>

<?php adminFooter(); ?>
