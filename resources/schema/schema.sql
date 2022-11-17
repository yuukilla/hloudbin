CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `firstname` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `lastname` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `date_joined` timestamp COLLATE utf8mb4_unicode_ci DEFAULT NOW(),
    `date_updated` timestamp COLLATE utf8mb4_unicode_ci DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` ( `username` ),
    UNIQUE KEY `email` ( `email` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
