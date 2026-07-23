<?php
/**
 * CHARACTERページ
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$characters = getCharacters();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>CHARACTER - モーキス 公式サイト</title>
    <link rel="stylesheet" href="css/retro.css">
    <script src="js/retro.js"></script>
</head>
<body>

<div class="content">
    <center>
        <h1 style="font-size: 28px;">
            <span class="rainbow-text">★ CHARACTER ★</span>
        </h1>
        <div class="separator" style="margin: 20px 0;">
            ★ ☆ ★ ☆ ★
        </div>
    </center>

    <?php if (empty($characters)): ?>
        <p style="color: #999; padding: 40px; text-align: center;">
            現在準備中です。しばらくお待ちください。
        </p>
    <?php else: ?>
        <?php foreach ($characters as $char): ?>
            <div class="character-box">
                <div class="character-name">
                    <?php echo h($char['name']); ?>
                    <?php if ($char['name_kana']): ?>
                        <span class="character-kana"><?php echo h($char['name_kana']); ?></span>
                    <?php endif; ?>
                </div>

                <table width="100%" cellpadding="5" cellspacing="0">
                    <tr>
                        <?php if ($char['image_path']): ?>
                            <td width="200" valign="top" align="center">
                                <img src="<?php echo h($char['image_path']); ?>"
                                     alt="<?php echo h($char['name']); ?>"
                                     style="max-width: 180px; border: 2px solid #9966cc;">
                            </td>
                        <?php else: ?>
                            <td width="200" valign="top" align="center">
                                <div style="width: 150px; height: 200px; background: #1a0813; border: 2px dashed #ff66cc; display: flex; align-items: center; justify-content: center; color: #ff7f00; font-size: 11px; font-weight: bold;">
                                    [ 立ち絵準備中 ]
                                </div>
                            </td>
                        <?php endif; ?>
                        <td valign="top">
                            <div class="character-info">
                                <?php if ($char['grade']): ?>
                                    <p><strong>学年:</strong> <?php echo h($char['grade']); ?></p>
                                <?php endif; ?>

                                <?php if ($char['club']): ?>
                                    <p><strong>部活:</strong> <?php echo h($char['club']); ?></p>
                                <?php endif; ?>

                                <?php if ($char['height']): ?>
                                    <p><strong>身長:</strong> <?php echo h($char['height']); ?></p>
                                <?php endif; ?>

                                <?php if ($char['description']): ?>
                                    <p style="margin-top: 15px; line-height: 1.7;">
                                        <?php echo nl2br(h($char['description'])); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
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
