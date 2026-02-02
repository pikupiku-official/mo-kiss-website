<?php
/**
 * 管理画面 - ダッシュボード
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance();

// 統計情報取得
$newsCount = $db->queryOne("SELECT COUNT(*) as count FROM news")['count'];
$charactersCount = $db->queryOne("SELECT COUNT(*) as count FROM characters")['count'];
$accessCount = getAccessCount();

// 最新のWhat's News
$latestNews = getNews(5);

adminHeader('ダッシュボード');
?>

<h1>ダッシュボード</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 8px; color: white;">
        <h3 style="color: white; margin-bottom: 10px; font-size: 1.1em;">What's New</h3>
        <div style="font-size: 2.5em; font-weight: bold;"><?php echo $newsCount; ?></div>
        <div style="margin-top: 10px; opacity: 0.9;">件の記事</div>
    </div>

    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; border-radius: 8px; color: white;">
        <h3 style="color: white; margin-bottom: 10px; font-size: 1.1em;">キャラクター</h3>
        <div style="font-size: 2.5em; font-weight: bold;"><?php echo $charactersCount; ?></div>
        <div style="margin-top: 10px; opacity: 0.9;">人登録済み</div>
    </div>

    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 30px; border-radius: 8px; color: white;">
        <h3 style="color: white; margin-bottom: 10px; font-size: 1.1em;">アクセス</h3>
        <div style="font-size: 2.5em; font-weight: bold;"><?php echo number_format($accessCount); ?></div>
        <div style="margin-top: 10px; opacity: 0.9;">回の訪問</div>
    </div>
</div>

<h2>クイックアクセス</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0;">
    <a href="news.php" class="btn" style="text-align: center; padding: 20px;">What's New管理</a>
    <a href="story.php" class="btn" style="text-align: center; padding: 20px;">STORY編集</a>
    <a href="characters.php" class="btn" style="text-align: center; padding: 20px;">キャラクター管理</a>
    <a href="system.php" class="btn" style="text-align: center; padding: 20px;">SYSTEM編集</a>
    <a href="special.php" class="btn" style="text-align: center; padding: 20px;">SPECIAL編集</a>
    <a href="bgm.php" class="btn" style="text-align: center; padding: 20px;">BGM管理</a>
    <a href="users.php" class="btn" style="text-align: center; padding: 20px;">ユーザー管理</a>
</div>

<h2>最新のWhat's News</h2>

<?php if (empty($latestNews)): ?>
    <p style="color: #999; padding: 20px; text-align: center;">まだWhat's Newsがありません</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th style="width: 120px;">日付</th>
                <th>内容</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($latestNews as $news): ?>
                <tr>
                    <td><?php echo formatDate($news['date']); ?></td>
                    <td><?php echo h($news['content']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div style="margin-top: 40px; padding: 20px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px;">
    <strong>重要なお知らせ:</strong>
    <ul style="margin: 10px 0 0 20px;">
        <li>本番環境では必ず <code>includes/config.php</code> のデータベース設定を変更してください</li>
        <li>セキュリティのため、デフォルトの管理者パスワードを変更してください</li>
        <li>本番環境では <code>display_errors</code> を無効化してください</li>
    </ul>
</div>

<?php adminFooter(); ?>
