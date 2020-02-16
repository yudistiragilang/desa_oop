/*
 Navicat Premium Data Transfer

 Source Server         : Localhost_PHP_7_3
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : localhost:3306
 Source Schema         : desa

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 16/02/2020 23:45:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `description` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES (1, 'superadmin', 'Superadmin');
INSERT INTO `role` VALUES (2, 'admin.sistem', 'Admin Sistem');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `real_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `role` int(11) NOT NULL,
  `phone` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `last_visit` datetime(0) NULL DEFAULT NULL,
  `created_date` datetime(0) NULL DEFAULT NULL,
  `inactive` tinyint(1) NULL DEFAULT NULL,
  `delete_mark` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'gilang', '$2y$10$r6H9O6AitxDrAJOCYAYxiORJeL59kWF1Rb9pLYGLH1oZ.qpXwVpFi', 'Gilang tes', 0, '147', 'yudhistiragilang1122@gmail.com', '2020-02-15 14:49:57', '2020-02-06 10:01:10', 0, 0);
INSERT INTO `users` VALUES (2, 'tes', '$2y$10$jLpKdMScf44AHoYM17cnWeM6JwsASH72MYuJ8zxzSth/.BwAfiU3W', 'tes', 0, '111', 'tes@tes.id', '2020-02-06 10:21:49', '2020-02-06 10:21:42', 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
