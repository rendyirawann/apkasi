/*
 Navicat Premium Data Transfer

 Source Server         : LOCAL
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : mpp_antrian

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 04/06/2026 15:39:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for wilayah_provinsi
-- ----------------------------
DROP TABLE IF EXISTS `wilayah_provinsi`;
CREATE TABLE `wilayah_provinsi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 108 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of wilayah_provinsi
-- ----------------------------
INSERT INTO `wilayah_provinsi` VALUES (11, 'Aceh', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (12, 'Sumatera Utara', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (13, 'Sumatera Barat', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (14, 'Riau', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (15, 'Jambi', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (16, 'Sumatera Selatan', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (17, 'Bengkulu', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (18, 'Lampung', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (19, 'Kepulauan Bangka Belitung', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (21, 'Kepulauan Riau', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (31, 'Dki Jakarta', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (32, 'Jawa Barat', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (33, 'Jawa Tengah', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (34, 'Di Yogyakarta', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (35, 'Jawa Timur', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (36, 'Banten', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (51, 'Bali', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (52, 'Nusa Tenggara Barat', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (53, 'Nusa Tenggara Timur', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (61, 'Kalimantan Barat', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (62, 'Kalimantan Tengah', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (63, 'Kalimantan Selatan', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (64, 'Kalimantan Timur', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (65, 'Kalimantan Utara', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (71, 'Sulawesi Utara', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (72, 'Sulawesi Tengah', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (73, 'Sulawesi Selatan', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (74, 'Sulawesi Tenggara', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (75, 'Gorontalo', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (76, 'Sulawesi Barat', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (81, 'Maluku', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (82, 'Maluku Utara', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (91, 'Papua Barat', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (92, 'Papua Barat Daya', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (94, 'Papua', '2022-06-17 14:03:31', '2023-12-26 13:48:23');
INSERT INTO `wilayah_provinsi` VALUES (95, 'Papua Selatan', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (96, 'Papua Tengah', '2022-06-17 14:03:31', '2022-06-17 14:03:31');
INSERT INTO `wilayah_provinsi` VALUES (97, 'Papua Pegunungan', '2022-06-17 14:03:31', '2022-06-17 14:03:31');

SET FOREIGN_KEY_CHECKS = 1;
