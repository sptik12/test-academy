--
-- Table structure for table `user`
--
--

CREATE TABLE `user`
(
    `id`                   int(11) NOT NULL AUTO_INCREMENT,
    `username`             varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
    `auth_key`             varchar(32) COLLATE utf8_unicode_ci  NOT NULL,
    `password_hash`        varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `verification_token`   varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `first_name`           varchar(64) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `last_name`            varchar(64) COLLATE utf8_unicode_ci  DEFAULT NULL,
    `email`                varchar(64) COLLATE utf8_unicode_ci NOT NULL,
    `status`               smallint(6) NOT NULL DEFAULT '1',
    `created_at`           int(11) NOT NULL,
    `updated_at`           int(11) NOT NULL,
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `order`
--
--

CREATE TABLE `order`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `title`       varchar(256) COLLATE utf8_unicode_ci NOT NULL,
    `first_name`  varchar(64) COLLATE utf8_unicode_ci  NOT NULL,
    `last_name`   varchar(64) COLLATE utf8_unicode_ci  NOT NULL,
    `phone`       varchar(32) COLLATE utf8_unicode_ci  NOT NULL,
    `comment`     text COLLATE utf8_unicode_ci         NOT NULL,
    `category_id` int(11) not null,
    `price`       float default 0,
    `status`      smallint(6) DEFAULT 0,
    `created_at`  int(11),
    `updated_at`  int(11),
    PRIMARY KEY (`id`),
    KEY           `ind_category_id` (`category_id`)
) CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `category`
--
--

CREATE TABLE `category`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `name`       varchar(256) COLLATE utf8_unicode_ci NOT NULL,
    `created_at` int(11),
    `updated_at` int(11),
    PRIMARY KEY (`id`)
) CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `event_log`
--
--
CREATE TABLE `event_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `record_id`  varchar(255),
    `table_name` varchar(255),
    `action_id`  int(11),
    `model_name` varchar(255),
    `title`      text,
    `status`     smallint(6),
    `owner_id`   int(11),
    `created_at` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY          `ind_model_name` (`model_name`),
    KEY          `ind_record_id` (`record_id`),
    KEY          `ind_action_id` (`action_id`),
    KEY          `ind_owner_id` (`owner_id`),
    KEY          `ind_created_at` (`created_at`)
) CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `event_log_change`
--
--
CREATE TABLE `event_log_change`
(
    `id`           bigint(10) NOT NULL AUTO_INCREMENT,
    `event_log_id` int(11) NOT NULL,
    `attribute`    varchar(255),
    `label`        varchar(255),
    `old_value`    text,
    `new_value`    text,
    PRIMARY KEY (`id`),
    KEY            `ind_event_log_id` (`event_log_id`)
) CHARSET=utf8 COLLATE=utf8_unicode_ci;
