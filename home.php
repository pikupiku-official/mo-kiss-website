<?php
/**
 * HOMEページ - What's New表示
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// 匿名Cookieを使ったユニーク訪問者カウンター
$accessStats = getAccessStats(true);
$accessCount = $accessStats['total'];

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
    <script src="js/retro.js"></script>
</head>
<body>

<!-- BGMは親フレームで再生制御されています -->

<div class="content">
    <center>
        <h1 style="font-size: 32px; margin: 20px 0 10px 0;">
            <span class="rainbow-text">モーキス 公式サイトへようこそ</span>
        </h1>
        <div style="font-size: 18px; color: #ff7f00; font-weight: normal; margin-bottom: 25px; text-shadow: 1px 1px 0px #ffffff, 2px 2px 2px rgba(44, 34, 53, 0.2);">
            「世紀末、今さら君と恋をした」
        </div>

        <img src="images/キービジュ.png"
             alt="モーキス キービジュアル"
             style="display: block; width: 100%; max-width: 720px; height: auto; margin: 0 auto 25px;">

        <p style="color: #2c2235; font-size: 12px; line-height: 1.4; font-weight: normal;">
            1999年6月、終末が迫る世界で描かれる恋愛シミュレーションゲーム<br>
            「モーキス」の公式サイトです。
        </p>

        <div class="separator" style="margin: 30px 0;">
            ★ ☆ ★ ☆ ★
        </div>
    </center>

    <!-- STORY セクション -->
    <div style="background: #1a0813; border: 2px ridge #ff66cc; padding: 20px; margin: 20px 0; color: #ffffff;">
        <center>
            <h2 style="font-size: 18px; color: #ff7f00; border: none; padding: 0; margin-bottom: 15px; font-weight: normal; text-shadow: 1px 1px 0px #1a0813;">
                ★ STORY ★
            </h2>
        </center>
        <p style="font-size: 12px; line-height: 1.8; text-align: left; text-indent: 1em; margin: 0;">
            1999年6月、東京都国分寺市。異様な空模様、突如現れる巨星──ノストラダムスの大予言が現実味を帯びる中、高校生活最後、そして世界最後の一月を、2年生のあなたはヒロインたちと過ごす。
        </p>
        <p style="font-size: 12px; line-height: 1.8; text-align: left; text-indent: 1em; margin: 10px 0 0 0;">
            当時のリアルな空気を追求した、映画的シナリオで紡ぐ女の子とのラブストーリーADV。家族を何より大切にする幼馴染の少女、誰をも寄せ付けない孤独な先輩。世界の終わりに際して、あの娘は何を求め、何を手放すまいとしているのか。
        </p>
    </div>

    <div class="separator" style="margin: 30px 0;">
        ★ ☆ ★ ☆ ★
    </div>

    <center>
        <h2>アクセスカウンター</h2>
        <p style="margin: 20px 0;">
            <?php echo displayCounter($accessCount); ?>
        </p>
        <p style="font-size: 12px; color: #2c2235; font-weight: normal; margin-bottom: 5px;">
            あなたは <strong style="color: #e6007e; font-size: 14px;"><?php echo number_format($accessCount); ?></strong> 人目の訪問者です （本日：<?php echo number_format($accessStats['today']); ?> / 昨日：<?php echo number_format($accessStats['yesterday']); ?>）
        </p>
        <div style="font-size: 10px; color: #665c6e; margin-bottom: 15px; font-family: 'MS Pゴシック', sans-serif;">
            （キリ番報告はBBSまで！）
        </div>
        <div style="margin: 15px auto; width: 80%; border: 1px dashed #e6007e; padding: 10px; background: #1a0813; font-size: 11px; color: #fdf5e6; text-align: left; line-height: 1.4;">
            <span style="color: #ff7f00; font-weight: normal;">⚠️ キリ番報告のお願い ⚠️</span><br>
            当サイトは<strong>キリ番（100, 500, 1000など切りが良い数字）</strong>を踏んだ方は、BBS（掲示板）でのご報告をお願いしております。管理人への報告なしの「踏み逃げ」は厳禁です！
        </div>
    </center>

    <div class="separator" style="margin: 30px 0;">
        ★ ☆ ★ ☆ ★
    </div>

    <div style="background: #fff9e6; border: 2px dashed #ffcc00; padding: 15px; margin: 20px 0;">
        <center>
            <strong style="color: #e6007e; font-size: 14px; text-shadow: 1px 1px 0px #ffffff;" class="blink">⚠️ お知らせ ⚠️</strong><br>
            <p style="color: #4d0e2b; font-size: 12px; margin-top: 10px; line-height: 1.4; font-weight: normal;">
                現在、サイト準備中です。<br>
                順次コンテンツを追加していきますので、お楽しみに！
            </p>
        </center>
    </div>

    <div class="notice" style="color: #665c6e; margin-top: 40px; text-align: center;">
        <p style="color: #e6007e; font-weight: normal;">◆ 推奨環境 ◆</p>
        <p>このサイトは Internet Explorer 4.0以上推奨 / 画面解像度 800×600以上推奨</p>

        <hr style="border: 0; border-top: 1px dotted #ccc; margin: 20px 0;">
        <p>&copy; 1999 MOKISS Project. All Rights Reserved.</p>
        <p><a href="/admin/" target="_blank" style="color: #999; font-size: 10px;">管理画面</a></p>
    </div>
</div>

</body>
</html>
