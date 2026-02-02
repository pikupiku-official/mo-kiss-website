<?php
/**
 * アクセスカウンターAPI
 * カウンターの数字画像を返す
 */

// パラメータ取得
$digit = isset($_GET['digit']) ? $_GET['digit'] : '0';

// 数字チェック
if (!is_numeric($digit) || $digit < 0 || $digit > 9) {
    $digit = '0';
}

// 画像サイズ
$width = 20;
$height = 25;

// 画像作成
$image = imagecreatetruecolor($width, $height);

// 色設定
$bgColor = imagecolorallocate($image, 0, 0, 0);  // 黒背景
$textColor = imagecolorallocate($image, 255, 0, 0);  // 赤文字
$borderColor = imagecolorallocate($image, 255, 215, 0);  // 金枠

// 背景塗りつぶし
imagefill($image, 0, 0, $bgColor);

// 枠線描画
imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

// 数字描画（中央寄せ）
imagestring($image, 5, 6, 5, $digit, $textColor);

// ヘッダー出力
header('Content-Type: image/gif');
header('Cache-Control: public, max-age=3600');

// 画像出力
imagegif($image);

// メモリ解放
imagedestroy($image);
