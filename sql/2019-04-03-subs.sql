ALTER TABLE `subs` CHANGE `page_mode` `page_mode` ENUM('best-comments','threads','best-threads','interview','answered','standard')  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT NULL;