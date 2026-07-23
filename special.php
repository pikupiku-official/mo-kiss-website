<?php
/**
 * SPECIALページ
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$special = getSpecialInfo();
$countdown = $special && $special['release_date'] ? getCountdown($special['release_date']) : null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>SPECIAL - モーキス 公式サイト</title>
    <link rel="stylesheet" href="css/retro.css">
    <script src="js/retro.js"></script>
</head>
<body>

<div class="content">
    <center>
        <h1 style="font-size: 28px;">
            <span class="rainbow-text">★ SPECIAL ★</span>
        </h1>
        <div class="separator" style="margin: 20px 0;">
            ★ ☆ ★ ☆ ★
        </div>
    </center>

    <?php if ($countdown && $countdown !== '発売済み'): ?>
        <div class="countdown-box">
            発売まであと <?php echo h($countdown); ?> ！
        </div>
    <?php elseif ($countdown === '発売済み'): ?>
        <div style="background: #66cc66; color: #ffffff; font-size: 20px; font-weight: bold; text-align: center; padding: 20px; border: 5px double #339933; margin: 20px 0;">
            ★ 発売中 ★
        </div>
    <?php endif; ?>

    <?php if ($special): ?>
        <div style="line-height: 1.8;">
            <?php echo $special['content']; ?>
        </div>
    <?php else: ?>
        <p style="color: #999; padding: 40px; text-align: center;">
            現在準備中です。しばらくお待ちください。
        </p>
    <?php endif; ?>

    <div class="separator" style="margin: 30px 0;">
        ★ ☆ ★ ☆ ★
    </div>

    <center>
        <p style="margin: 20px 0;">
            <a href="home.php" style="color: #0066cc; font-weight: bold;">« HOMEに戻る</a>
        </p>
    </center>

    <div class="notice">
        <p>&copy; 1999 MOKISS Project. All Rights Reserved.</p>
    </div>
</div>

</body>
</html>
