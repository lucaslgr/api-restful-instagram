--
-- Database: `project-api-devstagram`
--
CREATE DATABASE IF NOT EXISTS `project-api-devstagram` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `project-api-devstagram`;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `url` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `id_user`, `url`) VALUES
(1, 2, 'phototest.jpg'),
(2, 3, 'dsadsaas'),
(4, 6, '1321312'),
(5, 7, '32131'),
(6, 10, '3ffdsfds'),
(7, 2, '0dsadsadas'),
(8, 4, 'sdasdsadsa');

-- --------------------------------------------------------

--
-- Table structure for table `photos_comments`
--

CREATE TABLE `photos_comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_photo` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `data_comment` datetime NOT NULL,
  `txt` text CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `photos_comments`
--

INSERT INTO `photos_comments` (`id`, `id_user`, `id_photo`, `data_comment`, `txt`) VALUES
(1, 3, 1, '0000-00-00 00:00:00', 'HAHAHA'),
(4, 7, 4, '0000-00-00 00:00:00', 'HEHEHEH'),
(5, 10, 6, '0000-00-00 00:00:00', 'HAUHAUHUA'),
(7, 6, 5, '0000-00-00 00:00:00', 'odsjajiodjaso'),
(8, 4, 2, '2020-04-01 18:22:12', 'Comentário sinistro');

-- --------------------------------------------------------

--
-- Table structure for table `photos_likes`
--

CREATE TABLE `photos_likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_photo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `photos_likes`
--

INSERT INTO `photos_likes` (`id`, `id_user`, `id_photo`) VALUES
(1, 3, 1),
(2, 4, 1),
(3, 6, 2),
(5, 10, 4),
(6, 3, 6),
(8, 4, 7),
(9, 4, 2),
(11, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `email` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `pass` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `avatar` varchar(100) CHARACTER SET utf8 DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `avatar`) VALUES
(3, 'Lucas', 'lucas@gmail.com', '$2y$10$p6qfJVns3PFwwXDIz1QTnOWdmUOTPL4iQcpiqO5pxkTAU9lxJncwi', ''),
(4, 'Lucas', 'lucas123@gmail.com', '$2y$10$en9/HgI/OFsCbDTBygM5KuhUE/5TKr9JgAB.94iclhnvoRNcZPZo6', ''),
(6, 'Norma', 'norma@gmail.com', '$2y$10$CW7yZrJUI9mrtAZPtYyRJ.FbzY6K4PPdV3WTuKMcJn5NIrU/f4NHe', ''),
(7, 'Sebastiao', 'sebastiao@gmail.com', '$2y$10$v6Anx/DlU0xKX0dr/QaTjeB2OaWdvlCidHnZX9e99MV0AXRffPrry', ''),
(10, 'Karine', 'karine@gmail.com', '$2y$10$d9RFNH0QRhzacWnqufc6oOseHtoB.eAAIqzVHtQmi7a91B3Ob3M2i', '');

-- --------------------------------------------------------

--
-- Table structure for table `users_following`
--

CREATE TABLE `users_following` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user_active` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `id_user_passive` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_following`
--

INSERT INTO `users_following` (`id`, `id_user_active`, `id_user_passive`) VALUES
(5, 4, 3),
(6, 4, 7),
(7, 6, 7),
(8, 7, 10),
(9, 10, 4),
(10, 3, 7),
(11, 10, 3),
(12, 4, 10),
(13, 4, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photos_comments`
--
ALTER TABLE `photos_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photos_likes`
--
ALTER TABLE `photos_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_following`
--
ALTER TABLE `users_following`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `photos_comments`
--
ALTER TABLE `photos_comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `photos_likes`
--
ALTER TABLE `photos_likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users_following`
--
ALTER TABLE `users_following`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
