-- =====================================================================
-- Manual DB sync script
-- Brings an existing production DB (still on the "first commit" schema)
-- up to date with everything added by:
--   69cd27d Add reward redemption and messaging integration
--   ae07340 Create Whatsapp chatbot
--   2026_09_22_000000_add_recipient_to_messages_info_table (Send To: user/admin/dealer)
--
-- Table prefix is "tbl_" (config/database.php, mysql connection).
-- Run this whole file inside ONE transaction. Take a full DB backup first.
-- =====================================================================

START TRANSACTION;

-- ---------------------------------------------------------------------
-- 1. reward_catalog_categories (new table)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_reward_catalog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NULL,
  `image` varchar(255) NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reward_catalog_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. reward_products (new table)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_reward_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(100) NULL,
  `short_description` varchar(500) NULL,
  `description` text NULL,
  `image` varchar(255) NULL,
  `points_required` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NULL,
  `stock` int(11) NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `ip` varchar(255) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reward_products_slug_unique` (`slug`),
  KEY `reward_products_category_id_index` (`category_id`),
  KEY `reward_products_status_index` (`status`),
  KEY `reward_products_points_required_index` (`points_required`),
  CONSTRAINT `reward_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `tbl_reward_catalog_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. reward_claims (new table, with the later "quantity", "claim_group_id"
--    and "redemption_code" columns folded in directly)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_reward_claims` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `claim_group_id` varchar(40) NULL,
  `user_id` int(11) NOT NULL,
  `reward_product_id` bigint(20) unsigned NULL,
  `customer_points_id` int(11) NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `points_per_unit` int(11) NOT NULL DEFAULT 0,
  `points_spent` int(11) NOT NULL,
  `dealer_id` int(11) NULL,
  `dealer_name` varchar(255) NULL,
  `dealer_mobile` varchar(20) NULL,
  `dealer_address` text NULL,
  `dealer_city` varchar(255) NULL,
  `dealer_pincode` varchar(20) NULL,
  `status` enum('Pending','Approved','Dispatched','Delivered','Rejected') NOT NULL DEFAULT 'Pending',
  `redemption_code` varchar(6) NULL,
  `code_generated_at` timestamp NULL DEFAULT NULL,
  `admin_remark` text NULL,
  `courier_name` varchar(255) NULL,
  `tracking_number` varchar(255) NULL,
  `dispatched_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `delivered_remark` varchar(255) NULL,
  `code_verified_at` timestamp NULL DEFAULT NULL,
  `added_from` enum('app','admin') NOT NULL DEFAULT 'app',
  `ip` varchar(255) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reward_claims_user_id_index` (`user_id`),
  KEY `reward_claims_dealer_id_index` (`dealer_id`),
  KEY `reward_claims_status_index` (`status`),
  KEY `reward_claims_reward_product_id_index` (`reward_product_id`),
  KEY `reward_claims_claim_group_id_index` (`claim_group_id`),
  KEY `tbl_reward_claims_redemption_code_index` (`redemption_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NOTE: the app never went live with the old single-column reward_claims
-- shape, so there is no backfill UPDATE needed here (unlike the original
-- migrations, which had to backfill quantity/points_per_unit for rows that
-- pre-dated those columns).

-- ---------------------------------------------------------------------
-- 4. website_settings - new columns
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_website_settings`
  ADD COLUMN `waba_number` varchar(20) NULL AFTER `whatsaap_api_key`,
  ADD COLUMN `sms_entity_id` varchar(50) NULL AFTER `waba_number`,
  ADD COLUMN `sms_api_key` varchar(255) NULL AFTER `sms_entity_id`,
  ADD COLUMN `fcm_service_account_json` text NULL AFTER `fcm_icon_url`;

-- ---------------------------------------------------------------------
-- 5. whatsapp_templates (new table)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_whatsapp_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_uid` varchar(64) NULL,
  `template_name` varchar(255) NOT NULL,
  `language` varchar(20) NOT NULL DEFAULT 'en',
  `category` varchar(50) NULL,
  `parameter_format` varchar(20) NOT NULL DEFAULT 'POSITIONAL',
  `header_type` varchar(20) NULL,
  `header_text` text NULL,
  `body_text` longtext NULL,
  `footer_text` text NULL,
  `buttons` longtext NULL,
  `components` longtext NULL,
  `variable_count` int(10) unsigned NOT NULL DEFAULT 0,
  `approval_status` varchar(30) NOT NULL DEFAULT 'APPROVED',
  `synced_at` timestamp NULL DEFAULT NULL,
  `ip` varchar(255) NULL,
  `site_id` int(10) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `whatsapp_templates_name_lang_unique` (`template_name`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. whatsapp_configurations (new table, with the later "name" column
--    folded in)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_whatsapp_configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `message_id` int(10) unsigned NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `language` varchar(20) NOT NULL DEFAULT 'en',
  `category` varchar(50) NULL,
  `variable_mapping` longtext NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `ip` varchar(255) NULL,
  `site_id` int(10) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Backfill name from the linked message title (mirrors migration 2026_09_09_000000)
UPDATE `tbl_whatsapp_configurations` c
  JOIN `tbl_messages_info` m ON m.id = c.message_id
  SET c.name = m.title WHERE c.name IS NULL;

-- ---------------------------------------------------------------------
-- 7. whatsapp_message_logs (new table)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_whatsapp_message_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wamid` varchar(191) NULL,
  `mobileno` varchar(20) NULL,
  `template_name` varchar(255) NULL,
  `message_id` int(10) unsigned NULL,
  `payload` longtext NULL,
  `response` longtext NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `error` text NULL,
  `status_at` timestamp NULL DEFAULT NULL,
  `ip` varchar(255) NULL,
  `site_id` int(10) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_whatsapp_message_logs_wamid_index` (`wamid`),
  KEY `tbl_whatsapp_message_logs_mobileno_index` (`mobileno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 8. sms_message_logs (new table)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_sms_message_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobileno` varchar(20) NULL,
  `message_id` int(10) unsigned NULL,
  `sender_id` varchar(20) NULL,
  `template_id` varchar(100) NULL,
  `message` text NULL,
  `request` text NULL,
  `response` longtext NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `provider_message_id` varchar(100) NULL,
  `client_ref_id` varchar(100) NULL,
  `error` text NULL,
  `ip` varchar(255) NULL,
  `site_id` int(10) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_sms_message_logs_mobileno_index` (`mobileno`),
  KEY `tbl_sms_message_logs_provider_message_id_index` (`provider_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 9. whatsapp_bot_sessions (new table)
-- ---------------------------------------------------------------------
CREATE TABLE `tbl_whatsapp_bot_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dealer_mobile` varchar(10) NOT NULL,
  `state` varchar(40) NOT NULL,
  `carpenter_user_id` int(10) unsigned NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_whatsapp_bot_sessions_dealer_mobile_unique` (`dealer_mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 10. messages_info - new channel columns (mirrors 2026_09_10_000001 +
--     2026_09_11_000000)
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_messages_info`
  ADD COLUMN `sms_template_id` varchar(100) NULL AFTER `mode`,
  ADD COLUMN `sms_sender_id` varchar(20) NULL AFTER `sms_template_id`,
  ADD COLUMN `sms_message` text NULL AFTER `sms_sender_id`,
  ADD COLUMN `sms_variable_mapping` longtext NULL AFTER `sms_message`,
  ADD COLUMN `app_message` text NULL AFTER `sms_variable_mapping`,
  ADD COLUMN `app_target_screen` varchar(40) NULL AFTER `app_message`;

-- Backfill: copy the legacy free-text message into the new sms/app columns
-- and rewrite {token} placeholders to {#var#} + a variable_mapping, exactly
-- as migration 2026_09_10_000001 did. Adjust table/column names if your
-- `messages_info` shape differs from what shipped in "first commit".
SET @sender_id = (SELECT `sender_id` FROM `tbl_website_settings` LIMIT 1);

UPDATE `tbl_messages_info`
SET
  `sms_template_id` = `template_id`,
  `sms_sender_id`   = @sender_id,
  `sms_message`     = NULLIF(
        REPLACE(REPLACE(REPLACE(REPLACE(`message`,
          '{user}', '{#var#}'),
          '{reward_points}', '{#var#}'),
          '{otp}', '{#var#}'),
          '{mobileno}', '{#var#}'),
      ''),
  `app_message` = NULLIF(
        REPLACE(REPLACE(`message`, '{user}', '{user_name}'), '{reward_points}', '{points}'),
      '')
WHERE `message` IS NOT NULL;

-- The exact per-row {#var#} position -> token mapping (sms_variable_mapping)
-- and the trimmed `mode` value (WhatsApp appended only where an active
-- mapping exists) depend on per-row token order and on tbl_whatsapp_configurations
-- data that will not exist until step 6 runs on your real message rows.
-- Recommended: run this block via `php artisan tinker` (or a one-off command)
-- using the same logic as
-- database/migrations/2026_09_10_000001_add_channel_fields_to_messages_info_table.php
-- instead of hand-writing per-row SQL, then move on to step 11.

-- ---------------------------------------------------------------------
-- 11. notifications - new column
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_notifications`
  ADD COLUMN `target_screen` varchar(40) NULL AFTER `description`;

-- ---------------------------------------------------------------------
-- 12. manager - soft-delete the old WhatsApp configuration menu entry
--     (mirrors 2026_09_10_000003; safe no-op if the row doesn't exist)
-- ---------------------------------------------------------------------
UPDATE `tbl_manager`
SET `deleted_at` = NOW()
WHERE `page_link` = 'whatsapp-configuration' AND `deleted_at` IS NULL;

-- ---------------------------------------------------------------------
-- 13. messages_info - recipient column (mirrors 2026_09_22_000000)
--     Who a message goes to: user (carpenter/customer), admin or dealer.
--     One event may have one message row per recipient - see
--     App\Message::recipients() / App\SendMessage::recipientFor().
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_messages_info`
  ADD COLUMN `recipient` varchar(20) NOT NULL DEFAULT 'user' AFTER `title`;

-- The only event that went to the admin before recipients existed.
UPDATE `tbl_messages_info`
SET `recipient` = 'admin'
WHERE `title` = 'Admin Registration Notification';

COMMIT;

-- =====================================================================
-- After running this file, mark all 24 migrations as already applied so
-- `php artisan migrate` does not try to run them again (replace :batch
-- with next batch number, e.g. SELECT MAX(batch)+1 FROM tbl_migrations
-- if you use a "tbl_" prefixed migrations table, or "migrations" if not):
-- =====================================================================
-- INSERT INTO `migrations` (`migration`, `batch`) VALUES
-- ('2026_08_27_000000_create_reward_catalog_categories_table', :batch),
-- ('2026_08_29_000000_create_reward_products_table', :batch),
-- ('2026_08_29_000001_create_reward_claims_table', :batch),
-- ('2026_08_31_000000_remove_status_from_reward_catalog_categories_table', :batch),
-- ('2026_09_07_000000_add_quantity_to_reward_claims_table', :batch),
-- ('2026_09_07_000001_add_claim_group_to_reward_claims_table', :batch),
-- ('2026_09_08_000000_add_waba_number_to_website_settings_table', :batch),
-- ('2026_09_08_000001_create_whatsapp_templates_table', :batch),
-- ('2026_09_08_000002_create_whatsapp_configurations_table', :batch),
-- ('2026_09_08_000003_add_celitix_fields_to_whatsapp_templates_table', :batch),
-- ('2026_09_08_000004_create_whatsapp_message_logs_table', :batch),
-- ('2026_09_09_000000_add_name_to_whatsapp_configurations_table', :batch),
-- ('2026_09_09_000001_migrate_legacy_whatsapp_variable_tokens', :batch),
-- ('2026_09_10_000000_add_sms_entity_id_to_website_settings_table', :batch),
-- ('2026_09_10_000001_add_channel_fields_to_messages_info_table', :batch),
-- ('2026_09_10_000002_create_sms_message_logs_table', :batch),
-- ('2026_09_10_000003_remove_whatsapp_configuration_menu', :batch),
-- ('2026_09_10_000004_add_sms_api_key_to_website_settings_table', :batch),
-- ('2026_09_11_000000_add_app_target_screen_to_messages_info_table', :batch),
-- ('2026_09_11_000001_add_target_screen_to_notifications_table', :batch),
-- ('2026_09_11_000002_add_fcm_service_account_json_to_website_settings_table', :batch),
-- ('2026_09_14_000000_add_redemption_code_to_reward_claims_table', :batch),
-- ('2026_09_14_000001_create_whatsapp_bot_sessions_table', :batch),
-- ('2026_09_22_000000_add_recipient_to_messages_info_table', :batch);
