<?php
/**
 * アクセスカウンターAPI
 * 7セグメントデジタル数字画像を動的生成する
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

// カラーパレット
$bgColor = imagecolorallocate($image, 0, 0, 0);       // 完全な黒背景
$borderColor = imagecolorallocate($image, 255, 215, 0); // 金枠
$ledOn = imagecolorallocate($image, 255, 0, 0);       // 点灯LED（赤）
$ledOff = imagecolorallocate($image, 50, 0, 0);       // 消灯LED（暗い赤、うっすらセグメントが見える表現）

// 背景塗りつぶし
imagefill($image, 0, 0, $bgColor);

// 金色の枠線を描画
imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

// 各セグメントの長方形座標定義 [x1, y1, x2, y2]
// a: 上, b: 右上, c: 右下, d: 下, e: 左下, f: 左上, g: 中央
$segments = [
    'a' => [4, 3, 15, 4],     // 上
    'f' => [3, 5, 4, 11],     // 左上
    'b' => [15, 5, 16, 11],    // 右上
    'g' => [5, 12, 14, 13],   // 中央
    'e' => [3, 14, 4, 20],    // 左下
    'c' => [15, 14, 16, 20],   // 右下
    'd' => [4, 21, 15, 22]    // 下
];

// 数字ごとにONにするセグメントを定義
$digits = [
    '0' => ['a', 'b', 'c', 'd', 'e', 'f'],
    '1' => ['b', 'c'],
    '2' => ['a', 'b', 'g', 'e', 'd'],
    '3' => ['a', 'b', 'g', 'c', 'd'],
    '4' => ['f', 'g', 'b', 'c'],
    '5' => ['a', 'f', 'g', 'c', 'd'],
    '6' => ['a', 'f', 'g', 'e', 'c', 'd'],
    '7' => ['a', 'b', 'c'],
    '8' => ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
    '9' => ['a', 'b', 'c', 'd', 'f', 'g']
];

$activeSegments = isset($digits[$digit]) ? $digits[$digit] : $digits['0'];

// すべてのセグメントを描画
foreach ($segments as $name => $coords) {
    // ONなら明るい赤、OFFなら暗い赤
    $color = in_array($name, $activeSegments) ? $ledOn : $ledOff;
    imagefilledrectangle($image, $coords[0], $coords[1], $coords[2], $coords[3], $color);
}

// ヘッダー出力
header('Content-Type: image/gif');
header('Cache-Control: public, max-age=3600');

// 画像出力
imagegif($image);

// メモリ解放
imagedestroy($image);
