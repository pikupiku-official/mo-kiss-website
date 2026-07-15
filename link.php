<?php
/**
 * LINKページ
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>LINK - モーキス 公式サイト</title>
    <link rel="stylesheet" href="css/retro.css">
    <script src="js/retro.js"></script>
</head>
<body>

<div class="content">
    <center>
        <h1 style="font-size: 28px;">
            <span class="rainbow-text">★ LINK ★</span>
        </h1>
        <div class="separator" style="margin: 20px 0;">
            ★ ☆ ★ ☆ ★
        </div>
    </center>

    <div style="margin: 20px auto; max-width: 500px;">
        <p style="font-size: 12px; text-align: center; color: #ff7f00; font-weight: normal; margin-bottom: 20px;">
            ◆ 外部関連リンク ◆
        </p>

        <!-- リンクテーブル -->
        <table class="deco-table" width="100%" cellpadding="10" cellspacing="0" style="border: 2px ridge #ff66cc; background: #1a0813; color: #ffffff;">
            <tr style="border-bottom: 1px solid #ff66cc;">
                <td width="30%" align="center" style="font-weight: normal; background: #300c18; color: #ff7f00;">Steamストア</td>
                <td>
                    <a href="https://store.steampowered.com/app/3966340/_/" target="_blank" style="font-size: 12px; font-weight: normal; color: #ff66cc; text-decoration: underline;">
                        「モーキス」Steam製品ページ
                    </a>
                    <p style="font-size: 10px; color: #999; margin: 5px 0 0 0;">Steamにて予約・ウィッシュリスト登録受付中！</p>
                </td>
            </tr>
            <tr>
                <td width="30%" align="center" style="font-weight: normal; background: #300c18; color: #ff7f00;">広報用X</td>
                <td>
                    <a href="https://x.com/mokiss_official" target="_blank" style="font-size: 12px; font-weight: normal; color: #ff66cc; text-decoration: underline;">
                        モーキス公式X（旧Twitter）
                    </a>
                    <p style="font-size: 10px; color: #999; margin: 5px 0 0 0;">最新の製品情報や開発進捗をつぶやきます。</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="separator" style="margin: 30px 0;">
        ★ ☆ ★ ☆ ★
    </div>

    <center>
        <p style="margin: 20px 0;">
            <a href="home.php" style="color: #3b52a2;">« HOMEに戻る</a>
        </p>
    </center>

    <div class="notice">
        <p>&copy; 1999 MOKISS Project. All Rights Reserved.</p>
    </div>
</div>

</body>
</html>
