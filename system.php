<?php
/**
 * SYSTEMページ
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page = getPageContent('system');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>SYSTEM - モーキス 公式サイト</title>
    <link rel="stylesheet" href="css/retro.css">
</head>
<body>

<div class="content">
    <center>
        <h1 style="font-size: 28px; color: #ff6699;">
            ★ SYSTEM ★
        </h1>
        <div class="separator" style="margin: 20px 0;">
            ★ ☆ ★ ☆ ★
        </div>
    </center>

    <?php if ($page): ?>
        <div style="line-height: 1.8;">
            <?php echo $page['content']; ?>
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
