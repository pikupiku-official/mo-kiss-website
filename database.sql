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

-- 匿名ユニーク訪問者（CookieのランダムIDをSHA-256化して保存）
CREATE TABLE IF NOT EXISTS access_visitors (
    visitor_hash CHAR(64) PRIMARY KEY,
    first_visited_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 日別ユニーク訪問者
CREATE TABLE IF NOT EXISTS access_daily_visitors (
    visit_date DATE NOT NULL,
    visitor_hash CHAR(64) NOT NULL,
    first_visited_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (visit_date, visitor_hash),
    INDEX idx_visit_date (visit_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== 初期データ投入 =====

-- デフォルト管理者ユーザー（パスワード: admin123）
INSERT INTO users (username, password) VALUES
('admin', '$2y$12$0ZC20SKq4FoLVDcNpJYZGeb0pYpmAnFqr6kEHM0Ytoep98l4Gn4My');

-- キャラクター初期データ
INSERT INTO characters (sort_order, name, name_kana, grade, club, height, description, image_path) VALUES
(1, '愛沼桃子', 'あいぬま ももこ', '二年生', 'バドミントン部', NULL, '健やかな身体の幼馴染。テニス部所属。ネアカで家庭的なので実はモテるが、当の本人は大好きな家族と出掛けたり喫茶店を巡ったりで隙が無い。', NULL),
(2, '舞田沙那子', 'まいた さなこ', '三年生', '帰宅部', NULL, '遅刻をした日、正門で出会った先輩。話しかけても返事は冷たく、ひとりが好きみたいだ。帰りの電車でゲームボーイカラーをしている。', NULL),
(3, '桔梗美鈴', 'ききょう みすず', '三年生', '元吹奏楽部', NULL, '抜群のルックスを持つ高嶺の花の先輩。吹奏楽部でコントラバスを弾いていた。いつも明るく楽しそうでそれ故人気が高いが、恋愛に関しては奥手。', NULL),
(4, '増田', 'ますた', '二年生', '野球部（元）', NULL, 'クラスのムードメーカーで主人公の親友。野球部を辞めた現在は、ロックバンドで一旗揚げるべく歌を特訓中。とにかくモテない。', NULL);

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
