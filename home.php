<?php
/**
 * HOMEページ - What's New表示
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// アクセスカウンター更新
$accessCount = getAccessCount(true);

// What's News取得
$newsList = getNews(10);

// BGM設定取得
$bgm = getBgmSetting();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>HOME - モーキス 公式サイト</title>
    <link rel="stylesheet" href="css/retro.css">
</head>
<body>

<?php if ($bgm['enabled']): ?>
<!-- BGMコントロール -->
<div class="bgm-control">
    <span style="color: #ffcc00; font-size: 11px;">♪ BGM:</span>
    <button onclick="playBgm()">ON</button>
    <button onclick="stopBgm()">OFF</button>
</div>

<audio id="bgm" loop>
    <source src="<?php echo h($bgm['file']); ?>" type="audio/mpeg">
</audio>

<script>
var bgmAudio = document.getElementById('bgm');
var bgmPlayed = false;

function playBgm() {
    bgmAudio.play();
    bgmPlayed = true;
}

function stopBgm() {
    bgmAudio.pause();
    bgmPlayed = false;
}

// 自動再生を試みる（ブラウザによってはブロックされる）
document.addEventListener('DOMContentLoaded', function() {
    if (!bgmPlayed) {
        bgmAudio.play().catch(function(error) {
            console.log('自動再生がブロックされました。BGMボタンで再生してください。');
        });
    }
});
</script>
<?php endif; ?>

<div class="content">
    <center>
        <h1 style="font-size: 32px; color: #ff6699; margin: 20px 0;">
            モーキス 公式サイトへようこそ
        </h1>

        <p style="color: #0066cc; font-size: 13px; line-height: 1.6;">
            1999年7月、終末が迫る世界で描かれる恋愛シミュレーションゲーム<br>
            「モーキス」の公式サイトです。
        </p>

        <div class="separator" style="margin: 30px 0;">
            ★ ☆ ★ ☆ ★
        </div>
    </center>

    <h2>What's New!</h2>

    <?php if (empty($newsList)): ?>
        <p style="color: #999; padding: 20px; text-align: center;">更新情報はまだありません。</p>
    <?php else: ?>
        <?php foreach ($newsList as $news): ?>
            <div class="news-item">
                <span class="news-date"><?php echo formatDate($news['date']); ?></span>
                <span><?php echo h($news['content']); ?></span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="separator" style="margin: 30px 0;">
        ★ ☆ ★ ☆ ★
    </div>

    <center>
        <h2>アクセスカウンター</h2>
        <p style="margin: 20px 0;">
            <?php echo displayCounter($accessCount); ?>
        </p>
        <p style="font-size: 11px; color: #666;">
            あなたは <strong style="color: #cc0066;"><?php echo number_format($accessCount); ?></strong> 人目の訪問者です
        </p>
    </center>

    <div class="separator" style="margin: 30px 0;">
        ★ ☆ ★ ☆ ★
    </div>

    <div style="background: #ffffcc; border: 2px dashed #ff9900; padding: 15px; margin: 20px 0;">
        <center>
            <strong style="color: #cc0000;">⚠ お知らせ ⚠</strong><br>
            <p style="color: #333; font-size: 12px; margin-top: 10px;">
                現在、サイト準備中です。<br>
                順次コンテンツを追加していきますので、お楽しみに！
            </p>
        </center>
    </div>

    <div class="notice" style="color: #666; margin-top: 40px;">
        <p>このサイトはInternet Explorer 4.0以上推奨</p>
        <p>画面解像度 800×600以上でご覧ください</p>
        <hr style="border: 0; border-top: 1px dotted #ccc; margin: 20px 0;">
        <p>&copy; 1999 MOKISS Project. All Rights Reserved.</p>
        <p><a href="/admin/" target="_blank" style="color: #999; font-size: 10px;">管理画面</a></p>
    </div>
</div>

</body>
</html>
