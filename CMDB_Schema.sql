-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.36-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,ANSI_QUOTES' */;


--
-- Create schema cmdb
--

CREATE DATABASE IF NOT EXISTS cmdb;
USE cmdb;

--
-- Definition of table "cmdb"."BusinessUnit"
--

DROP TABLE IF EXISTS "cmdb"."BusinessUnit";
CREATE TABLE  "cmdb"."BusinessUnit" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(48) NOT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_BusUnitName" ("name")
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."BusinessUnit"
--
INSERT INTO "cmdb"."BusinessUnit" ("id","name") VALUES 
 (5,'Canadian Remittance'),
 (4,'Cash Management Services'),
 (2,'Computer and Network Services'),
 (7,'Corporate and Collaborative Applications'),
 (8,'Enterprise Delivery Support Services'),
 (3,'Image Delivery Services'),
 (1,'Integrated Statement Solutions'),
 (6,'QA and Testing');

--
-- Definition of table "cmdb"."BusinessUnitFunctionalArea"
--

DROP TABLE IF EXISTS "cmdb"."BusinessUnitFunctionalArea";
CREATE TABLE  "cmdb"."BusinessUnitFunctionalArea" (
  "business_unit_id" int(10) unsigned NOT NULL DEFAULT '0',
  "functional_area_id" int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY ("business_unit_id","functional_area_id"),
  KEY "functional_area_id" ("functional_area_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."BusinessUnitFunctionalArea"
--
INSERT INTO "cmdb"."BusinessUnitFunctionalArea" ("business_unit_id","functional_area_id") VALUES 
 (1,1),
 (1,24),
 (1,29),
 (2,5),
 (2,6),
 (2,7),
 (2,8),
 (2,9),
 (2,10),
 (2,11),
 (2,12),
 (2,24),
 (2,27),
 (3,13),
 (3,14),
 (3,15),
 (3,24),
 (3,25),
 (4,24),
 (5,17),
 (5,18),
 (5,19),
 (5,24),
 (5,26),
 (6,20),
 (6,24),
 (7,21),
 (7,22),
 (7,24),
 (8,23),
 (8,24);

--
-- Definition of table "cmdb"."Device"
--

DROP TABLE IF EXISTS "cmdb"."Device";
CREATE TABLE  "cmdb"."Device" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(255) NOT NULL,
  "asset_tag" mediumint(8) unsigned DEFAULT NULL,
  "vendor" varchar(255) DEFAULT NULL,
  "manufacturer" varchar(255) DEFAULT NULL,
  "model" varchar(255) DEFAULT NULL,
  "serial_num" varchar(255) DEFAULT NULL,
  "support_type" varchar(8) DEFAULT 'SYMCOR',
  "support_sla" time DEFAULT NULL,
  "support_end" date DEFAULT NULL,
  "warranty_expire" date DEFAULT NULL,
  "maintenance_vendor" varchar(255) DEFAULT NULL,
  "maintenance_expire" date DEFAULT NULL,
  "purchase" date DEFAULT NULL,
  "install" date DEFAULT NULL,
  "replacement" date DEFAULT NULL,
  "end_life" date DEFAULT NULL,
  "device_type_id" int(10) unsigned DEFAULT NULL,
  "location_id" int(10) unsigned DEFAULT NULL,
  "op_area_id" int(10) unsigned DEFAULT NULL,
  "op_status_id" int(10) unsigned DEFAULT NULL,
  "application_area_id" int(10) unsigned DEFAULT NULL,
  "functional_area_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_Device" ("name"),
  KEY "idx_Asset" ("asset_tag"),
  KEY "idx_device_type_id" ("device_type_id"),
  KEY "idx_op_area_id" ("op_area_id"),
  KEY "idx_op_status_id" ("op_status_id"),
  KEY "idx_functional_area_id" ("functional_area_id"),
  KEY "idx_application_area_id" ("application_area_id"),
  KEY "idx_location_id" ("location_id")
) ENGINE=MyISAM AUTO_INCREMENT=14088 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."DeviceComment"
--

DROP TABLE IF EXISTS "cmdb"."DeviceComment";
CREATE TABLE  "cmdb"."DeviceComment" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "comment" text NOT NULL,
  "device_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated_at" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_device_id" ("device_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."DeviceComment"
--

--
-- Definition of table "cmdb"."DeviceDependency"
--

DROP TABLE IF EXISTS "cmdb"."DeviceDependency";
CREATE TABLE  "cmdb"."DeviceDependency" (
  "from_device_id" int(10) unsigned NOT NULL DEFAULT '0',
  "to_device_id" int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY ("from_device_id","to_device_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Definition of table "cmdb"."DeviceOwner"
--

DROP TABLE IF EXISTS "cmdb"."DeviceOwner";
CREATE TABLE  "cmdb"."DeviceOwner" (
  "device_id" int(10) unsigned NOT NULL,
  "owner_id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("device_id","owner_id")
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."DeviceType"
--

DROP TABLE IF EXISTS "cmdb"."DeviceType";
CREATE TABLE  "cmdb"."DeviceType" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "type" varchar(24) NOT NULL DEFAULT 'Server',
  PRIMARY KEY ("id"),
  KEY "idx_DeviceType" ("type")
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."DeviceType"
--
INSERT INTO "cmdb"."DeviceType" ("id","type") VALUES 
 (12,'Antenna'),
 (11,'Camera'),
 (22,'CD Burner'),
 (17,'Clock'),
 (19,'Controller'),
 (20,'Data Collector'),
 (1,'Desktop'),
 (10,'Encoder'),
 (21,'Fax'),
 (2,'Firewall'),
 (14,'ILOM'),
 (15,'Inserter'),
 (3,'Laptop'),
 (18,'Print Cutter'),
 (4,'Printer'),
 (5,'Router'),
 (8,'Scanner'),
 (6,'Server'),
 (9,'Sorter'),
 (13,'Storage'),
 (7,'Switch'),
 (16,'Tape Library'),
 (23,'Hub'),
 (24,'Phone'),
 (25,'IP Phone');

--
-- Definition of table "cmdb"."FunctionalArea"
--

DROP TABLE IF EXISTS "cmdb"."FunctionalArea";
CREATE TABLE  "cmdb"."FunctionalArea" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(40) NOT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_FuncAreaName" ("name")
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."Location"
--

DROP TABLE IF EXISTS "cmdb"."Location";
CREATE TABLE  "cmdb"."Location" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "floor" tinyint(3) unsigned DEFAULT NULL,
  "room" varchar(32) DEFAULT NULL,
  "row" tinyint(3) unsigned DEFAULT NULL,
  "cabinet" varchar(16) DEFAULT NULL,
  "site_id" int(10) unsigned DEFAULT NULL,
  "device_id" int(10) unsigned DEFAULT NULL,
  "latitude" double DEFAULT NULL,
  "longitude" double DEFAULT NULL,
  "jack" varchar(16) DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_site_id" ("site_id")
) ENGINE=MyISAM AUTO_INCREMENT=21442 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."NetworkComment"
--

DROP TABLE IF EXISTS "cmdb"."NetworkComment";
CREATE TABLE  "cmdb"."NetworkComment" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "comment" text NOT NULL,
  "network_prop_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated_at" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_network_prop_id" ("network_prop_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."NetworkDependency"
--

DROP TABLE IF EXISTS "cmdb"."NetworkDependency";
CREATE TABLE  "cmdb"."NetworkDependency" (
  "from_net_prop_id" int(10) unsigned NOT NULL DEFAULT '0',
  "to_net_prop_id" int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY ("from_net_prop_id","to_net_prop_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."NetworkProperty"
--

DROP TABLE IF EXISTS "cmdb"."NetworkProperty";
CREATE TABLE  "cmdb"."NetworkProperty" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "ip_addr" int(10) unsigned NOT NULL DEFAULT '0',
  "mac" char(17) DEFAULT NULL,
  "nic" varchar(32) NOT NULL DEFAULT 'NIC1',
  "conn_blade" smallint(5) unsigned DEFAULT NULL,
  "conn_port" smallint(5) unsigned DEFAULT NULL,
  "conn_vlan" int(10) unsigned DEFAULT NULL,
  "conn_speed" smallint(5) unsigned DEFAULT NULL,
  "conn_medium" varchar(24) DEFAULT NULL,
  "duplex" char(2) DEFAULT NULL,
  "nic_alias" varchar(64) DEFAULT NULL,
  "description" varchar(255) DEFAULT NULL,
  "device_id" int(10) unsigned NOT NULL,
  "functional_area_id" int(10) unsigned NOT NULL,
  "created" datetime DEFAULT NULL,
  "updated" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_Address" ("ip_addr","nic","mac"),
  KEY "idx_MAC" ("mac","nic","ip_addr"),
  KEY "idx_device_id" ("device_id"),
  KEY "idx_functional_area_id" ("functional_area_id")
) ENGINE=MyISAM AUTO_INCREMENT=45030 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."OperationalArea"
--

DROP TABLE IF EXISTS "cmdb"."OperationalArea";
CREATE TABLE  "cmdb"."OperationalArea" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "code" char(3) NOT NULL,
  "type" varchar(32) NOT NULL DEFAULT 'Server',
  PRIMARY KEY ("id"),
  KEY "idx_OpArea" ("code","type")
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."OperationalArea"
--
INSERT INTO "cmdb"."OperationalArea" ("id","code","type") VALUES 
 (3,'BCP','Backup Site'),
 (4,'CAT','Client Acceptance Testing'),
 (2,'CRP','Corporate'),
 (7,'DEV','Development'),
 (1,'PRD','Production'),
 (6,'SIT','Systems Integration Testing'),
 (8,'SMG','Systems Management'),
 (5,'UAT','User Acceptance Testing');

--
-- Definition of table "cmdb"."OperationalStatus"
--

DROP TABLE IF EXISTS "cmdb"."OperationalStatus";
CREATE TABLE  "cmdb"."OperationalStatus" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "status" varchar(16) NOT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_OpStatus" ("status")
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."OperationalStatus"
--
INSERT INTO "cmdb"."OperationalStatus" ("id","status") VALUES 
 (4,'Decommissioned'),
 (3,'Maintenance'),
 (1,'Operational'),
 (2,'Unreachable');

--
-- Definition of table "cmdb"."SecurityComment"
--

DROP TABLE IF EXISTS "cmdb"."SecurityComment";
CREATE TABLE  "cmdb"."SecurityComment" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "comment" text NOT NULL,
  "security_prop_id" int(10) unsigned NOT NULL DEFAULT '0',
  "created" datetime DEFAULT NULL,
  "updated_at" datetime DEFAULT NULL,
  PRIMARY KEY ("id","security_prop_id"),
  KEY "security_prop_id" ("security_prop_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."SecurityComment"
--

--
-- Definition of table "cmdb"."SecurityProperty"
--

DROP TABLE IF EXISTS "cmdb"."SecurityProperty";
CREATE TABLE  "cmdb"."SecurityProperty" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "zone" varchar(16) NOT NULL DEFAULT 'SYMCOR',
  "device_id" int(10) unsigned DEFAULT NULL,
  "functional_area_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_device_id" ("device_id"),
  KEY "idx_functional_area_id" ("functional_area_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."SecurityProperty"
--

--
-- Definition of table "cmdb"."Service"
--

DROP TABLE IF EXISTS "cmdb"."Service";
CREATE TABLE  "cmdb"."Service" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(32) NOT NULL,
  "port" smallint(5) unsigned NOT NULL DEFAULT '0',
  "protocol" char(3) NOT NULL DEFAULT 'tcp',
  PRIMARY KEY ("id"),
  KEY "idx_ServicePort" ("port","name","protocol")
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."Service"
--
INSERT INTO "cmdb"."Service" ("id","name","port","protocol") VALUES 
 (1,'FTP Data',20,'tcp'),

--
-- Definition of table "cmdb"."ServiceComment"
--

DROP TABLE IF EXISTS "cmdb"."ServiceComment";
CREATE TABLE  "cmdb"."ServiceComment" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "comment" text NOT NULL,
  "service_prop_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated_at" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_service_prop_id" ("service_prop_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."ServiceComment"
--

--
-- Definition of table "cmdb"."ServiceDependency"
--

DROP TABLE IF EXISTS "cmdb"."ServiceDependency";
CREATE TABLE  "cmdb"."ServiceDependency" (
  "from_service_prop_id" int(10) unsigned NOT NULL DEFAULT '0',
  "to_service_prop_id" int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY ("from_service_prop_id","to_service_prop_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."ServiceProperty"
--

DROP TABLE IF EXISTS "cmdb"."ServiceProperty";
CREATE TABLE  "cmdb"."ServiceProperty" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "service_id" int(10) unsigned NOT NULL,
  "device_id" int(10) unsigned NOT NULL,
  "functional_area_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_device_id" ("device_id"),
  KEY "idx_service_id" ("service_id"),
  KEY "idx_functional_area_id" ("functional_area_id")
) ENGINE=MyISAM AUTO_INCREMENT=28936 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."Site"
--

DROP TABLE IF EXISTS "cmdb"."Site";
CREATE TABLE  "cmdb"."Site" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "code" char(7) NOT NULL,
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_Site" ("code","name")
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."SystemComment"
--

DROP TABLE IF EXISTS "cmdb"."SystemComment";
CREATE TABLE  "cmdb"."SystemComment" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "comment" text NOT NULL,
  "system_prop_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated_at" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_system_prop_id" ("system_prop_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."SystemComment"
--

--
-- Definition of table "cmdb"."SystemProperty"
--

DROP TABLE IF EXISTS "cmdb"."SystemProperty";
CREATE TABLE  "cmdb"."SystemProperty" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "type" varchar(8) NOT NULL DEFAULT 'PHYSICAL',
  "firmware_version" varchar(10) DEFAULT NULL,
  "os" varchar(32) DEFAULT NULL,
  "os_version" varchar(10) DEFAULT NULL,
  "os_patch_version" varchar(10) DEFAULT NULL,
  "num_cpu" decimal(4,2) DEFAULT NULL,
  "cpu_type" varchar(32) DEFAULT NULL,
  "ram" varchar(6) DEFAULT NULL,
  "internal_disks" tinyint(3) unsigned DEFAULT NULL,
  "internal_storage" varchar(8) DEFAULT NULL,
  "device_id" int(10) unsigned NOT NULL,
  "functional_area_id" int(10) unsigned DEFAULT NULL,
  "created" datetime DEFAULT NULL,
  "updated" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "idx_device_id" ("device_id"),
  KEY "idx_functional_area_id" ("functional_area_id")
) ENGINE=MyISAM AUTO_INCREMENT=8601 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."User"
--

DROP TABLE IF EXISTS "cmdb"."User";
CREATE TABLE  "cmdb"."User" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "logon" varchar(16) NOT NULL,
  "fname" varchar(30) NOT NULL,
  "lname" varchar(30) NOT NULL,
  "password" char(40) NOT NULL,
  "email" varchar(60) NOT NULL,
  "is_admin" tinyint(3) unsigned NOT NULL DEFAULT '0',
  "change_password" tinyint(3) unsigned NOT NULL DEFAULT '1',
  "logon_enabled" tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  KEY "idx_User" ("logon","password","is_admin","change_password")
) ENGINE=MyISAM AUTO_INCREMENT=3718 DEFAULT CHARSET=latin1;

--
-- Definition of table "cmdb"."UserFunctionalArea"
--

DROP TABLE IF EXISTS "cmdb"."UserFunctionalArea";
CREATE TABLE  "cmdb"."UserFunctionalArea" (
  "user_id" int(10) unsigned NOT NULL DEFAULT '0',
  "functional_area_id" int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY ("user_id","functional_area_id"),
  KEY "functional_area_id" ("functional_area_id")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Definition of table "cmdb"."device_comment_index"
--

DROP TABLE IF EXISTS "cmdb"."device_comment_index";
CREATE TABLE  "cmdb"."device_comment_index" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "keyword" varchar(200) NOT NULL DEFAULT '',
  "field" varchar(50) NOT NULL DEFAULT '',
  "position" bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY ("id","keyword","field","position")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."device_comment_index"
--

--
-- Definition of table "cmdb"."migration_version"
--

DROP TABLE IF EXISTS "cmdb"."migration_version";
CREATE TABLE  "cmdb"."migration_version" (
  "version" int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."migration_version"
--
INSERT INTO "cmdb"."migration_version" ("version") VALUES 
 (1);

--
-- Definition of table "cmdb"."network_comment_index"
--

DROP TABLE IF EXISTS "cmdb"."network_comment_index";
CREATE TABLE  "cmdb"."network_comment_index" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "keyword" varchar(200) NOT NULL DEFAULT '',
  "field" varchar(50) NOT NULL DEFAULT '',
  "position" bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY ("id","keyword","field","position")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."network_comment_index"
--

--
-- Definition of table "cmdb"."security_comment_index"
--

DROP TABLE IF EXISTS "cmdb"."security_comment_index";
CREATE TABLE  "cmdb"."security_comment_index" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "security_prop_id" int(10) unsigned NOT NULL DEFAULT '0',
  "keyword" varchar(200) NOT NULL DEFAULT '',
  "field" varchar(50) NOT NULL DEFAULT '',
  "position" bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY ("id","security_prop_id","keyword","field","position")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."security_comment_index"
--

--
-- Definition of table "cmdb"."service_comment_index"
--

DROP TABLE IF EXISTS "cmdb"."service_comment_index";
CREATE TABLE  "cmdb"."service_comment_index" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "keyword" varchar(200) NOT NULL DEFAULT '',
  "field" varchar(50) NOT NULL DEFAULT '',
  "position" bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY ("id","keyword","field","position")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."service_comment_index"
--

--
-- Definition of table "cmdb"."system_comment_index"
--

DROP TABLE IF EXISTS "cmdb"."system_comment_index";
CREATE TABLE  "cmdb"."system_comment_index" (
  "id" int(10) unsigned NOT NULL DEFAULT '0',
  "keyword" varchar(200) NOT NULL DEFAULT '',
  "field" varchar(50) NOT NULL DEFAULT '',
  "position" bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY ("id","keyword","field","position")
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table "cmdb"."system_comment_index"
--



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
