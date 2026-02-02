-- モーキス公式サイト データベース初期化スクリプト
-- 作成日: 2026-02-01

-- データベースの作成（必要に応じて）
-- CREATE DATABASE IF NOT EXISTS mokiss_site DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE mokiss_site;

-- ユーザー（管理者）テーブル
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- What's Newテーブル
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date (date DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ページコンテンツテーブル（STORY, SYSTEM等）
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) UNIQUE NOT NULL COMMENT 'story, system等',
    title VARCHAR(100) NOT NULL,
    content LONGTEXT NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- キャラクターテーブル
CREATE TABLE IF NOT EXISTS characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sort_order INT DEFAULT 0,
    name VARCHAR(50) NOT NULL,
    name_kana VARCHAR(50) COMMENT 'ふりがな',
    grade VARCHAR(20) COMMENT '学年',
    club VARCHAR(50) COMMENT '部活',
    height VARCHAR(20) COMMENT '身長',
    description TEXT COMMENT '紹介文',
    image_path VARCHAR(255) COMMENT '画像パス',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SPECIAL（発売情報）テーブル
CREATE TABLE IF NOT EXISTS special (
    id INT AUTO_INCREMENT PRIMARY KEY,
    release_date DATE COMMENT '発売予定日（カウントダウン用）',
    content LONGTEXT COMMENT '発売情報テキスト',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- サイト設定テーブル（カウンター、BGM等）
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- アクセスカウンターテーブル
CREATE TABLE IF NOT EXISTS access_counter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    count INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== 初期データ投入 =====

-- デフォルト管理者ユーザー（パスワード: admin123）
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ページコンテンツ初期値
INSERT INTO pages (slug, title, content) VALUES
('story', 'STORY', '<h2>物語</h2>\n<p>1999年7月、東京都国分寺市――</p>\n<p>ノストラダムスの大予言が現実のものとなり、世界は終末へのカウントダウンを始めた。</p>\n<p>そんな混沌とした世界で、主人公は6人の少女たちと出会う。</p>\n<p>残された時間は、わずか。</p>\n<p>果たして、君は誰と最後の夏を過ごすのか――</p>'),
('system', 'SYSTEM', '<h2>ゲームシステム</h2>\n<p>本作は、スケジュール管理型の恋愛シミュレーションゲームです。</p>\n<h3>基本システム</h3>\n<ul>\n<li>期間：1999年7月1日〜7月31日（31日間）</li>\n<li>スケジュール選択式で物語が進行</li>\n<li>選択によってヒロインとの関係が変化</li>\n<li>マルチエンディング対応</li>\n</ul>\n<h3>特徴</h3>\n<ul>\n<li>フルボイス対応</li>\n<li>美麗なイベントCG</li>\n<li>90年代を再現した演出</li>\n</ul>');

-- キャラクター初期データ
INSERT INTO characters (sort_order, name, name_kana, grade, club, height, description, image_path) VALUES
(1, '烏丸神無', 'からすま かんな', '2年', '水泳部', '174cm', '孤高のギャル。全国大会出場経験を持つ競泳選手。クールな外見とは裏腹に、実は寂しがり屋な一面も。', 'images/characters/kanna.png'),
(2, '桔梗美鈴', 'ききょう みすず', '3年', '元吹奏楽部', '170cm', '憧れの先輩。モデル体型の美人で、誰からも慕われる存在。優しく包容力があり、母性的な魅力を持つ。', 'images/characters/misuzu.png'),
(3, '愛沼桃子', 'あいぬま ももこ', '2年', 'バドミントン部', '158cm', '朗らかなムードメーカー。実家は猫喫茶を営んでおり、動物好き。明るく元気で、周囲を笑顔にする太陽のような存在。', 'images/characters/momoko.png'),
(4, '舞田沙那子', 'まいた さなこ', '3年', '帰宅部', '165cm', 'クールビューティ。一見近寄りがたいが、実は甘えんぼな一面を持つ。読書が趣味で、図書館によくいる。', 'images/characters/sanako.png'),
(5, '宮月深依里', 'みやつき みより', '2年', '帰宅部', '162cm', 'ミステリアスな少女。よく隣の席になる不思議な縁がある。何を考えているのか分からないが、時折見せる笑顔が印象的。', 'images/characters/miyori.png'),
(6, '伊織紅', 'いおり くれない', '1年', '弓道部', '156cm', '母性のある後輩。年下ながら落ち着いた雰囲気を持つ。ちょっと小馬鹿にしてくる毒舌系だが、根は優しい。', 'images/characters/kurenai.png');

-- SPECIAL初期データ
INSERT INTO special (release_date, content) VALUES
('2026-12-31', '<h2>発売情報</h2>\n<p>「モーキス」は2026年12月31日発売予定！</p>\n<p>世紀末に相応しい、この日を選びました。</p>\n<h3>価格</h3>\n<p>通常版：6,800円（税別）</p>\n<p>限定版：9,800円（税別）※サントラCD・設定資料集付き</p>\n<h3>動作環境</h3>\n<p>OS：Windows 10/11</p>\n<p>CPU：Intel Core i3以上推奨</p>\n<p>メモリ：4GB以上</p>\n<p>HDD：2GB以上の空き容量</p>');

-- サイト設定初期値
INSERT INTO settings (setting_key, setting_value) VALUES
('bgm_enabled', '1'),
('bgm_file', 'sounds/bgm/main.mp3'),
('site_title', 'モーキス 公式サイト'),
('meta_description', '1999年7月、終末が迫る世界で少女との恋愛を描く恋愛シミュレーションゲーム');

-- アクセスカウンター初期値
INSERT INTO access_counter (count) VALUES (0);

-- What's New初期データ
INSERT INTO news (date, content) VALUES
('2026-02-01', 'ホームページ開設！よろしくお願いします！'),
('2026-02-01', 'キャラクター紹介ページを公開しました'),
('2026-02-01', 'STORYページを公開しました');
