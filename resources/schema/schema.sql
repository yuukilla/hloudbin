CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `firstname` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `lastname` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `avatar_id` int(11) DEFAULT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `date_joined` timestamp COLLATE utf8mb4_unicode_ci DEFAULT NOW(),
    `date_updated` timestamp COLLATE utf8mb4_unicode_ci DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` ( `username` ),
    UNIQUE KEY `email` ( `email` ),
    FOREIGN KEY (`avatar_id`) REFERENCES `storage.files`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `storage.files`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL, 
    `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `visible` int(1) NOT NULL DEFAULT 1,
    `upload_date` timestamp DEFAULT NOW(),
    
    PRIMARY KEY(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

ALTER TABLE `users`
    ADD CONSTRAINT fk_UserStorageAvatar
    FOREIGN KEY (`avatar_id`) REFERENCES `storage.files`(`id`);