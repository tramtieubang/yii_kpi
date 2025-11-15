-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th10 15, 2025 lúc 03:52 PM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `yii_kpi`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `group_code` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  KEY `fk_auth_item_group_code` (`group_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`, `group_code`) VALUES
('/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//ajaxcrud', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//controller', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//crud', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//extension', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//form', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//index', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//model', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('//module', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/asset/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/asset/compress', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/asset/template', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/cache/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/cache/flush', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/cache/flush-all', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/cache/flush-schema', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/cache/index', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/default/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/default/db-explain', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/default/download-mail', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/default/index', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/default/toolbar', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/default/view', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/user/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/user/reset-identity', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/debug/user/set-identity', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/fixture/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/fixture/load', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/fixture/unload', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gii/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gii/default/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gii/default/action', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gii/default/diff', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gii/default/index', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gii/default/preview', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gii/default/view', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/gridview/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/gridview/export/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/gridview/export/download', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/gridview/grid-edited-row/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/gridview/grid-edited-row/back', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/hello/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/hello/index', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/help/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/help/index', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/help/list', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/help/list-action-options', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/help/usage', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/message/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/message/config', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/message/config-template', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/message/extract', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/create', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/down', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/fresh', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/history', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/mark', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/new', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/redo', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/to', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/migrate/up', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/serve/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/serve/index', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/site/*', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/about', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/captcha', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/check-session', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/contact', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/error', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/index', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/login', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/site/logout', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user-management/*', 3, NULL, NULL, NULL, 1763208126, 1763208126, NULL),
('/user-management/auth/change-own-password', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user-permission/set', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user-permission/set-roles', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/bulk-activate', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/bulk-deactivate', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/bulk-delete', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/change-password', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/create', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/delete', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/grid-page-size', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/index', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/update', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user-management/user/view', 3, NULL, NULL, NULL, 1763208127, 1763208127, NULL),
('/user_management/*', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/default/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/default/bulkdelete', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/default/create', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/default/delete', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/default/index', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/default/update', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/default/view', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/permission-route/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/permission-route/index', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/permission-route/refresh-routes', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission/permission-route/save-routes', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/permission_group/*', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/default/*', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/default/bulkdelete', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/default/create', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/default/delete', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/default/index', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/default/update', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/default/view', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/group-add-permission/*', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/permission_group/group-add-permission/index', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/role/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/default/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/default/bulkdelete', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/default/create', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/default/delete', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/default/index', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/default/update', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/default/view', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/role-permission/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/role-permission/index', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/role/role-permission/save-permissions', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/session_manager/*', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/*', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/bulkdelete', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/create', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/delete', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/index', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/logout-device', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/revoke', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/revoke-all', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/update', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/session_manager/default/view', 3, NULL, NULL, NULL, 1763212549, 1763212549, NULL),
('/user_management/user/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/captcha', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/change-own-password', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/confirm-email', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/confirm-email-receive', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/confirm-registration-email', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/login', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/logout', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/password-recovery', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/password-recovery-receive', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/auth/registration', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/bulkdelete', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/change-password', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/create', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/delete', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/index', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/save-change-password', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/update', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/default/view', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/user-permission/*', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/user-permission/ajax-get-by-role', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/user-permission/index', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('/user_management/user/user-permission/save-roles', 3, NULL, NULL, NULL, 1763212550, 1763212550, NULL),
('Admin', 1, 'Admin', NULL, NULL, 1763208126, 1763208126, NULL),
('assignRolesToUsers', 2, 'Assign roles to users', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('bindUserToIp', 2, 'Bind user to IP', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('changeOwnPassword', 2, 'Change own password', NULL, NULL, 1763208127, 1763208127, 'userCommonPermissions'),
('changeUserPassword', 2, 'Change user password', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('commonPermission', 2, 'Common permission', NULL, NULL, 1763208126, 1763208126, NULL),
('createUsers', 2, 'Create users', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('deleteUsers', 2, 'Delete users', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('editUserEmail', 2, 'Edit user email', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('editUsers', 2, 'Edit users', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('viewRegistrationIp', 2, 'View registration IP', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('viewUserEmail', 2, 'View user email', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('viewUserRoles', 2, 'View user roles', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('viewUsers', 2, 'View users', NULL, NULL, 1763208127, 1763208127, 'userManagement'),
('viewVisitLog', 2, 'View visit log', NULL, NULL, 1763208127, 1763208127, 'userManagement');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('changeOwnPassword', '/user-management/auth/change-own-password'),
('assignRolesToUsers', '/user-management/user-permission/set'),
('assignRolesToUsers', '/user-management/user-permission/set-roles'),
('editUsers', '/user-management/user/bulk-activate'),
('editUsers', '/user-management/user/bulk-deactivate'),
('deleteUsers', '/user-management/user/bulk-delete'),
('changeUserPassword', '/user-management/user/change-password'),
('createUsers', '/user-management/user/create'),
('deleteUsers', '/user-management/user/delete'),
('viewUsers', '/user-management/user/grid-page-size'),
('viewUsers', '/user-management/user/index'),
('editUsers', '/user-management/user/update'),
('viewUsers', '/user-management/user/view'),
('Admin', 'assignRolesToUsers'),
('Admin', 'changeOwnPassword'),
('Admin', 'changeUserPassword'),
('Admin', 'createUsers'),
('Admin', 'deleteUsers'),
('Admin', 'editUsers'),
('editUserEmail', 'viewUserEmail'),
('assignRolesToUsers', 'viewUserRoles'),
('Admin', 'viewUsers'),
('assignRolesToUsers', 'viewUsers'),
('changeUserPassword', 'viewUsers'),
('createUsers', 'viewUsers'),
('deleteUsers', 'viewUsers'),
('editUsers', 'viewUsers');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_item_group`
--

DROP TABLE IF EXISTS `auth_item_group`;
CREATE TABLE IF NOT EXISTS `auth_item_group` (
  `code` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `auth_item_group`
--

INSERT INTO `auth_item_group` (`code`, `name`, `created_at`, `updated_at`) VALUES
('userCommonPermissions', 'User common permission', 1763208127, 1763208127),
('userManagement', 'User management', 1763208127, 1763208127);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migration`
--

DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1763208121),
('m140608_173539_create_user_table', 1763208125),
('m140611_133903_init_rbac', 1763208125),
('m140808_073114_create_auth_item_group_table', 1763208126),
('m140809_072112_insert_superadmin_to_user', 1763208126),
('m140809_073114_insert_common_permisison_to_auth_item', 1763208126),
('m141023_141535_create_user_visit_log', 1763208126),
('m141116_115804_add_bind_to_ip_and_registration_ip_to_user', 1763208126),
('m141121_194858_split_browser_and_os_column', 1763208126),
('m141201_220516_add_email_and_email_confirmed_to_user', 1763208126),
('m141207_001649_create_basic_user_permissions', 1763208127),
('m251115_121641_create_table_user_sessions', 1763209206);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `superadmin` smallint DEFAULT '0',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `registration_ip` varchar(15) DEFAULT NULL,
  `bind_to_ip` varchar(255) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `email_confirmed` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `confirmation_token`, `status`, `superadmin`, `created_at`, `updated_at`, `registration_ip`, `bind_to_ip`, `email`, `email_confirmed`) VALUES
(1, 'superadmin', '-2oFFDfaH-TJFAYQcT3E34HdLUH0TGl0', '$2y$13$Ya6jHkGQD9iSSadbstoMi.PlAYTzVhJ/7Yn/71EeGoiw4NOLcJrs2', NULL, 1, 1, 1763208126, 1763208126, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `session_id` varchar(64) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `last_activity` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `revoked_by_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `idx-user_sessions-user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `user_id`, `session_id`, `ip_address`, `user_agent`, `device_name`, `login_time`, `last_activity`, `logout_time`, `revoked_by_admin`) VALUES
(1, 1, 'kovu8ktt0s50lf3nv81bah27or', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Windows / Chrome', '2025-11-15 19:53:46', '2025-11-15 19:53:46', '2025-11-15 20:47:46', 0),
(2, 1, 'sbd69qrq3328pd15bjkdimpdjs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 'Windows / Firefox', '2025-11-15 20:14:35', '2025-11-15 20:14:35', '2025-11-15 20:51:19', 0),
(3, 1, '71qj9jpgencdobol29a9tthgiu', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Windows / Chrome', '2025-11-15 21:28:15', '2025-11-15 21:28:15', NULL, 0),
(4, 1, 'laib9o5r8pkv24tlcov8ofpufe', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 'Windows / Firefox', '2025-11-15 21:50:37', '2025-11-15 21:50:37', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_visit_log`
--

DROP TABLE IF EXISTS `user_visit_log`;
CREATE TABLE IF NOT EXISTS `user_visit_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `language` char(2) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `user_id` int DEFAULT NULL,
  `visit_time` int NOT NULL,
  `browser` varchar(30) DEFAULT NULL,
  `os` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Đang đổ dữ liệu cho bảng `user_visit_log`
--

INSERT INTO `user_visit_log` (`id`, `token`, `ip`, `language`, `user_agent`, `user_id`, `visit_time`, `browser`, `os`) VALUES
(1, '691877da50f27', '127.0.0.1', 'vi', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, 1763211226, 'Chrome', 'Windows'),
(2, '69187cbb90ffa', '127.0.0.1', 'vi', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 1, 1763212475, 'Firefox', 'Windows'),
(3, '69188dff3954f', '127.0.0.1', 'vi', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, 1763216895, 'Chrome', 'Windows'),
(4, '6918933d705ca', '127.0.0.1', 'vi', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 1, 1763218237, 'Firefox', 'Windows');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auth_item_group_code` FOREIGN KEY (`group_code`) REFERENCES `auth_item_group` (`code`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `user_visit_log`
--
ALTER TABLE `user_visit_log`
  ADD CONSTRAINT `user_visit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
