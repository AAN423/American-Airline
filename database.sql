-- =====================================================
--  Database untuk American Airlines PTFS Web
--  Import file ini lewat phpMyAdmin di cPanel InfinityFree
-- =====================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `discord_id` VARCHAR(32) NOT NULL UNIQUE,
  `username` VARCHAR(100) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `roles` TEXT DEFAULT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `last_login` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recruitment` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `position` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `apply_link` VARCHAR(500) NOT NULL,
  `open_until` DATE NOT NULL,
  `is_open` TINYINT(1) NOT NULL DEFAULT 1,
  `created_by` VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
