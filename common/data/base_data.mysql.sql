--
-- Initial data for table `user`
--
INSERT INTO `user` (`id`, `username`, `password_hash`, `password_reset_token`, `verification_token`, `first_name`, `last_name`, `email`, `status`, `created_at`, `updated_at`, `auth_key`) VALUES
(1, 'admin@test.com', '$2y$13$Dml34cYGphvGcN0UQ8O6Uuon0oZTZ360f1bYcSFKaVIW.2sxUA1Rq', NULL, NULL, 'Иван', 'Иванов', 'admin@test.com', 1, 1673685221, 1673843728, ''),
(2, 'manager@test.com', '$2y$13$cdcwfCQM.bwhulbXS8a4WuILGQx8Lmeuii023hClQtu5VS6l9/IRW', NULL, NULL, 'Сергей', 'Алексеев', 'manager@test.com', 1, 1673774290, 1673844005, 'LiRwETjo6kjVjTTN4bsTbxHpV6i7_2JV');

--
-- Initial data for table `auth_assignment`
--
INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES ('admin', '1', UNIX_TIMESTAMP());
INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES ('manager', '2', UNIX_TIMESTAMP());

--
-- Initial data for table `category`
--
INSERT INTO `category` (`name`, `created_at`) VALUES ('Яблоки', UNIX_TIMESTAMP());
INSERT INTO `category` (`name`, `created_at`) VALUES ('Апельсины', UNIX_TIMESTAMP());
INSERT INTO `category` (`name`, `created_at`) VALUES ('Мандарины', UNIX_TIMESTAMP());
