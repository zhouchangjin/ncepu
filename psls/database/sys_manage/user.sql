DROP TABLE IF EXISTS `tt_user`;
DROP TABLE IF EXISTS `department_info`;
DROP TABLE IF EXISTS `role_info`;
DROP TABLE IF EXISTS `right_info`;
DROP TABLE IF EXISTS `role_to_right`;

CREATE TABLE `department_info` (
  `department_info_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `department_name` varchar(50) DEFAULT NULL COMMENT '公司名称',
  `cdate` timestamp NULL DEFAULT NULL,
  `description` text COMMENT '描述',
  `address` varchar(200) DEFAULT NULL COMMENT '公司地址',
  PRIMARY KEY (`department_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='部门信息表';


CREATE TABLE `role_info` (
  `role_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) DEFAULT NULL COMMENT '角色描述',
  `status` mediumint(9) DEFAULT '0',
  `role_alias` varchar(50) DEFAULT NULL,
  `cdate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='角色信息表';

CREATE TABLE `right_info` (
  `right_description` varchar(50) DEFAULT NULL COMMENT '权限描述',
  `right_code` bigint(20) NOT NULL DEFAULT '0' COMMENT '权限编码',
  PRIMARY KEY (`right_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限信息表';

CREATE TABLE `tt_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL COMMENT '帐号',
  `password` varchar(64) DEFAULT NULL COMMENT '密码',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `role_id` bigint(20) DEFAULT NULL COMMENT '角色',
  `department_info_id` bigint(20) DEFAULT NULL COMMENT '部门',
  `gender` tinyint(3) DEFAULT '0' COMMENT '性别',
  `birthdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '出生日期',
  `cdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建日期',
  `edate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` bigint(20) DEFAULT NULL COMMENT '状态',
  `contactnumber` varchar(60) DEFAULT NULL COMMENT '联系电话'
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `department_info_id` (`department_info_id`),
  CONSTRAINT `tt_user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role_info` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tt_user_ibfk_2` FOREIGN KEY (`department_info_id`) REFERENCES `department_info` (`department_info_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='用户帐号表';

CREATE TABLE `dept_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dept_id` bigint(20) DEFAULT NULL COMMENT '部门',
  `role_id` bigint(20) DEFAULT NULL COMMENT '角色',
  `cdate` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

CREATE TABLE `role_to_right` (
  `cdate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `role_id` bigint(20) DEFAULT NULL,
  `right_code` bigint(20) DEFAULT NULL,
  KEY `role_id` (`role_id`),
  KEY `role_to_right_ibfk_2` (`right_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限对照表';


