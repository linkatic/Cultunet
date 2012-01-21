CREATE TABLE IF NOT EXISTS
 `#__js_job_categories` (
  `id` int(11) NOT NULL auto_increment,
  `cat_value` varchar(255) default NULL,
  `cat_title` varchar(255) default NULL,
  `isactive` smallint(1) default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
 `#__js_job_salaryrange` (
  `id` int(11) NOT NULL auto_increment,
  `rangevalue` varchar(255) default NULL,
  `rangestart` varchar(255) default NULL,
  `rangeend` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
)TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_companies` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `category` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `url` varchar(255) default NULL,
  `logofilename` varchar(100) default NULL,
  `logoisfile` tinyint(1) default '-1',
  `logo` blob,
  `smalllogofilename` varchar(100) default NULL,
  `smalllogoisfile` tinyint(1) default '-1',
  `smalllogo` tinyblob,
  `aboutcompanyfilename` varchar(100) default NULL,
  `aboutcompanyisfile` tinyint(1) default '-1',
  `aboutcompanyfilesize` varchar(100) default NULL,
  `aboutcompany` mediumblob,
  `contactname` varchar(255) NOT NULL default '',
  `contactphone` varchar(255) default NULL,
  `companyfax` varchar(250) default NULL,
  `contactemail` varchar(255) NOT NULL default '',
  `since` datetime default NULL,
  `companysize` varchar(255) default NULL,
  `income` varchar(255) default NULL,
  `description` text,
  `country` varchar(255) NOT NULL default '0',
  `state` varchar(255) default NULL,
  `county` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `zipcode` varchar(15) default NULL,
  `address1` varchar(255) default NULL,
  `address2` varchar(255) default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime default NULL,
  `hits` int(11) default NULL,
  `metadescription` text,
  `metakeywords` text,
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)TYPE=MyISAM;

 CREATE TABLE IF NOT EXISTS
	`#__js_job_jobs`
		(
		  `id` int(11) NOT NULL auto_increment,
		  `uid` varchar(255) NOT NULL default '',
		  `companyid` int(11) default NULL,
		  `title` varchar(255) NOT NULL default '',
		  `jobcategory` varchar(255) NOT NULL default '',
		  `jobtype` tinyint(1) unsigned default '0',
		  `jobstatus` tinyint(3) NOT NULL default '1',
		  `jobsalaryrange` varchar(255) default '',
		  `salaryrangetype` varchar(20) default NULL,
		  `hidesalaryrange` tinyint(1) default '1',
		  `description` text,
		  `qualifications` text,
		  `prefferdskills` text,
		  `applyinfo` text,
		  `company` varchar(255) NOT NULL default '',
		  `country` varchar(255) default '',
		  `state` varchar(255) default '',
		  `county` varchar(255) default '',
		  `city` varchar(255) default '',
		  `zipcode` varchar(10) default '',
		  `address1` varchar(255) default '',
		  `address2` varchar(255) default '',
		  `companyurl` varchar(255) default '',
		  `contactname` varchar(255) default '',
		  `contactphone` varchar(255) default '',
		  `contactemail` varchar(255) default '',
		  `showcontact` tinyint(1) unsigned default '0',
		  `noofjobs` int(11) unsigned NOT NULL default '1',
		  `reference` varchar(255) NOT NULL default '',
		  `duration` varchar(255) NOT NULL default '',
		  `heighestfinisheducation` varchar(255) default '',
		  `created` datetime NOT NULL default '0000-00-00 00:00:00',
		  `created_by` int(11) unsigned NOT NULL default '0',
		  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
		  `modified_by` int(11) unsigned NOT NULL default '0',
		  `hits` int(11) unsigned NOT NULL default '0',
		  `experience` int(11) default '0',
		  `startpublishing` datetime NOT NULL default '0000-00-00 00:00:00',
		  `stoppublishing` datetime NOT NULL default '0000-00-00 00:00:00',
		  `department` varchar(255) default NULL,
		  `shift` varchar(255) default NULL,
		  `sendemail` tinyint(1) NOT NULL default '0',
		  `metadescription` text,
		  `metakeywords` text,
		  `agreement` text,
		  `ordering` tinyint(3) NOT NULL default '0',
		  `aboutjobfile` varchar(50) default NULL,
		  `status` int(11) default '1',
		  PRIMARY KEY  (`id`),
		  KEY `jobcategory` (`jobcategory`),
		  KEY `jobsalaryrange` (`jobsalaryrange`)
		) TYPE=MyISAM;
		
CREATE TABLE IF NOT EXISTS
`#__js_job_jobapply` (
  `Id` int(11) NOT NULL auto_increment,
  `jobid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `cvid` int(11) default NULL,
  `apply_date` datetime default NULL,
  PRIMARY KEY  (`Id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_jobsearches` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `searchname` varchar(50) NOT NULL,
  `jobtitle` varchar(255) default NULL,
  `category` int(11) default NULL,
  `jobtype` int(11) default NULL,
  `jobstatus` int(11) default NULL,
  `salaryrange` int(11) default NULL,
  `heighesteducation` int(11) default NULL,
  `shift` int(11) default NULL,
  `experience` varchar(30) default NULL,
  `durration` varchar(30) default NULL,
  `startpublishing` datetime default NULL,
  `stoppublishing` datetime default NULL,
  `company` int(11) default NULL,
  `country_istext` tinyint(1) default NULL,
  `country` varchar(50) default NULL,
  `state_istext` tinyint(1) default NULL,
  `state` varchar(50) default NULL,
  `county_istext` tinyint(1) default NULL,
  `county` varchar(50) default NULL,
  `city_istext` tinyint(1) default NULL,
  `city` varchar(50) default NULL,
  `zipcode_istext` tinyint(1) default NULL,
  `zipcode` varchar(50) default NULL,
  `created` datetime default NULL,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)

) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_jobstatus` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `isactive` tinyint(1) default 1,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_jobtypes` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `isactive` tinyint(1) default 1,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_shifts` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `isactive` tinyint(1) default 1,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_config` (
  `configname` varchar(100) NOT NULL default '',
  `configvalue` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`configname`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_userallow` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default '0',
  `empallow` int(11) default '1',
  `joballow` int(11) default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_coverletters` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `hits` int(11) default NULL,
  `published` tinyint(1) NOT NULL,
  `searchable` tinyint(1) default NULL,
  `status` tinyint(1) default NULL,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_emailtemplates` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `templatefor` varchar(50) default NULL,
  `title` varchar(50) default NULL,
  `subject` varchar(255) default NULL,
  `body` text,
  `status` tinyint(1) default NULL,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_fieldsordering` (
  `id` int(11) NOT NULL auto_increment,
  `field` varchar(50) NOT NULL,
  `fieldtitle` varchar(50) default NULL,
  `ordering` int(11) default NULL,
  `section` varchar(20) default NULL,
  `fieldfor` tinyint(2) default NULL,
  `published` tinyint(1) default NULL,
  `sys` tinyint(1) NOT NULL,
  `cannotunpublish` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_filters` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `country_istext` tinyint(1) default NULL,
  `country` varchar(50) default NULL,
  `state_istext` tinyint(1) default NULL,
  `state` varchar(50) default NULL,
  `county_istext` tinyint(1) default NULL,
  `county` varchar(50) default NULL,
  `city_istext` tinyint(1) default NULL,
  `city` varchar(50) default NULL,
  `zipcode` varchar(20) default NULL,
  `category` int(11) default NULL,
  `jobtype` int(11) default NULL,
  `jobstatus` int(11) default NULL,
  `heighesteducation` int(11) default NULL,
  `salaryrange` int(11) default NULL,
  `created` datetime default NULL,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)

) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_heighesteducation` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `isactive` tinyint(1) default 1,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_resumesearches` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) default NULL,
  `searchname` varchar(50) NOT NULL,
  `application_title` varchar(255) default NULL,
  `nationality` varchar(50) default NULL,
  `gender` tinyint(2) default NULL,
  `iamavailable` tinyint(1) default '0',
  `category` int(11) default NULL,
  `jobtype` int(11) default NULL,
  `salaryrange` int(11) default NULL,
  `education` int(11) default NULL,
  `experience` varchar(30) default NULL,
  `created` datetime default NULL,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_roles` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(50) default NULL,
  `rolefor` tinyint(4) default NULL,
  `companies` int(11) default NULL,
  `jobs` int(11) default NULL,
  `resumes` int(11) default NULL,
  `coverletters` int(11) default NULL,
  `searchjob` int(11) default NULL,
  `searchresume` int(11) default NULL,
  `savesearchresume` int(11) default NULL,
  `savesearchjob` int(11) default NULL,
  `published` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_userfields` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL,
  `description` mediumtext,
  `type` varchar(50) NOT NULL default '',
  `maxlength` int(11) default NULL,
  `size` int(11) default NULL,
  `required` tinyint(4) default '0',
  `ordering` int(11) default NULL,
  `cols` int(11) default NULL,
  `rows` int(11) default NULL,
  `value` varchar(50) default NULL,
  `default` int(11) default NULL,
  `published` tinyint(1) NOT NULL default '1',
  `fieldfor` tinyint(2) NOT NULL default '0',
  `readonly` tinyint(1) NOT NULL default '0',
  `calculated` tinyint(1) NOT NULL default '0',
  `sys` tinyint(4) NOT NULL default '0',
  `params` mediumtext,
  PRIMARY KEY  (`id`)

) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_userfieldvalues` (
  `id` int(11) NOT NULL auto_increment,
  `field` int(11) NOT NULL default '0',
  `fieldtitle` varchar(255) NOT NULL default '',
  `fieldvalue` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `sys` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS
`#__js_job_userfield_data` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `referenceid` int(11) NOT NULL,
  `field` int(10) unsigned default NULL,
  `data` varchar(1000) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
	
CREATE TABLE IF NOT EXISTS
`#__js_job_userroles` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `dated` datetime default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
	
CREATE TABLE IF NOT EXISTS
 `#__js_job_countries` (
  `id` int(11) NOT NULL auto_increment,
  `loc` char(2) default NULL,
  `code` char(2) default NULL,
  `name` varchar(100) default NULL,
  `enabled` char(1) default 'Y',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `code` (`code`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS
 `#__js_job_states` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(50) default NULL,
  `name` varchar(100) default NULL,
  `enabled` char(1) default 'Y',
  `countrycode` varchar(5) NOT NULL default 'US',
  PRIMARY KEY  (`id`),
  KEY `code` (`code`),
  KEY `enabled` (`enabled`),
  KEY `countrycode` (`countrycode`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS
 `#__js_job_counties` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(50) default NULL,
  `name` varchar(100) default NULL,
  `enabled` char(1) default 'Y',
  `countrycode` varchar(5) NOT NULL default 'PK',
  `statecode` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `code` (`code`),
  KEY `countrystate` (`countrycode`,`statecode`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS
 `#__js_job_cities` (
  `id` int(8) NOT NULL auto_increment,
  `code` varchar(50) default NULL,
  `name` varchar(100) default NULL,
  `enabled` char(1) default 'Y',
  `countrycode` varchar(5) NOT NULL default 'US',
  `statecode` varchar(50) default NULL,
  `countycode` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `code` (`code`),
  KEY `countrystate` (`countrycode`,`statecode`),
  KEY `countrystatecounty` (`countrycode`,`statecode`,`countycode`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS
 `#__js_job_zips` (
  `id` int(8) NOT NULL auto_increment,
  `code` varchar(30) default NULL,
  `enabled` char(1) default 'Y',
  `countrycode` varchar(5) NOT NULL default 'US',
  `statecode` varchar(50) default NULL,
  `countycode` varchar(50) default NULL,
  `citycode` varchar(50) default NULL,
  `latitude` float default NULL,
  `longitude` float default NULL,
  PRIMARY KEY  (`id`),
  KEY `code` (`code`),
  KEY `countrystatecountycity` (`countrycode`,`statecode`,`countycode`,`citycode`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS
	`#__js_job_resume`
		(
		  `id` int(11) NOT NULL auto_increment,

		  `uid` varchar(90) default NULL,

		  `create_date` datetime NOT NULL default '0000-00-00 00:00:00',

		  `modified_date` datetime default NULL,

		  `published` tinyint(1) default 1,

		  `hits` int(11) default NULL,

		  `application_title` text NOT NULL,

		  `first_name` text NOT NULL,

		  `last_name` text NOT NULL,

		  `middle_name` text,

		  `gender` varchar(10) default NULL,

		  `email_address` text,

		  `home_phone` varchar(60) NOT NULL,

		  `work_phone` varchar(60) default NULL,

		  `cell` varchar(60) default NULL,

		  `nationality` varchar(50) default NULL,

		  `iamavailable` tinyint(1) default NULL,

		  `searchable` tinyint(1) default '1',

		  `photo` varchar(50) default NULL,

		  `job_category` varchar(100) default NULL,

		  `jobsalaryrange` int(11) default NULL,

		  `jobtype` varchar(60) default NULL,

		  `heighestfinisheducation` varchar(60) default NULL,

		  `address_country` varchar(100) default NULL,

		  `address_state` varchar(60) default NULL,

		  `address_county` varchar(100) default NULL,

		  `address_city` varchar(100) default NULL,

		  `address_zipcode` varchar(60) default NULL,

		  `address` text,

		  `institute` varchar(100) default NULL,

		  `institute_country` varchar(100) default NULL,

		  `institute_state` varchar(100) default NULL,

		  `institute_county` varchar(100) default NULL,

		  `institute_city` varchar(100) default NULL,

		  `institute_address` varchar(150) default NULL,

		  `institute_certificate_name` varchar(100) default NULL,

		  `institute_study_area` text,

		  `employer` varchar(250) default NULL,

		  `employer_position` varchar(150) default NULL,

		  `employer_resp` text,

		  `employer_pay_upon_leaving` varchar(250) default NULL,

		  `employer_supervisor` varchar(100) default NULL,

		  `employer_from_date` varchar(60) default NULL,

		  `employer_to_date` varchar(60) default NULL,

		  `employer_leave_reason` text,

		  `employer_country` varchar(100) default NULL,

		  `employer_state` varchar(100) default NULL,

		  `employer_county` varchar(100) default NULL,

		  `employer_city` varchar(100) default NULL,

		  `employer_zip` varchar(60) default NULL,

		  `employer_phone` varchar(60) default NULL,

		  `employer_address` varchar(150) default NULL,

		  `filename` varchar(50) default NULL,

		  `filetype` varchar(50) default NULL,

		  `filesize` int(11) default NULL,

		  `filecontent` mediumblob,

		  `field1` text,

		  `field2` text,

		  `field3` text,

		  `status` int(11) NOT NULL default '1',

		  `resume` text,

		  `institute1` varchar(100) default NULL,

		  `institute1_country` varchar(100) default NULL,

		  `institute1_state` varchar(100) default NULL,

		  `institute1_county` varchar(100) default NULL,

		  `institute1_city` varchar(100) default NULL,

		  `institute1_address` varchar(150) default NULL,

		  `institute1_study_area` text,

		  `institute1_certificate_name` varchar(100) default NULL,

		  `institute2` varchar(100) default NULL,

		  `institute2_country` varchar(100) default NULL,

		  `institute2_state` varchar(100) default NULL,

		  `institute2_county` varchar(100) default NULL,

		  `institute2_city` varchar(100) default NULL,

		  `institute2_address` varchar(150) default NULL,

		  `institute2_certificate_name` varchar(100) default NULL,

		  `institute2_study_area` text,

		  `institute3` varchar(100) default NULL,

		  `institute3_country` varchar(100) default NULL,

		  `institute3_state` varchar(100) default NULL,

		  `institute3_county` varchar(100) default NULL,

		  `institute3_city` varchar(100) default NULL,

		  `institute3_address` varchar(150) default NULL,

		  `institute3_study_area` text,

		  `institute3_certificate_name` varchar(100) default NULL,

		  `employer1` varchar(250) default NULL,

		  `employer1_position` varchar(150) default NULL,

		  `employer1_resp` text,

		  `employer1_pay_upon_leaving` varchar(250) default NULL,

		  `employer1_supervisor` varchar(100) default NULL,

		  `employer1_from_date` varchar(60) default NULL,

		  `employer1_to_date` varchar(60) default NULL,

		  `employer1_leave_reason` text,

		  `employer1_country` varchar(100) default NULL,

		  `employer1_state` varchar(100) default NULL,

		  `employer1_county` varchar(100) default NULL,

		  `employer1_city` varchar(100) default NULL,

		  `employer1_zip` varchar(60) default NULL,

		  `employer1_phone` varchar(60) default NULL,

		  `employer1_address` varchar(150) default NULL,

		  `employer2` varchar(250) default NULL,

		  `employer2_position` varchar(150) default NULL,

		  `employer2_resp` text,

		  `employer2_pay_upon_leaving` varchar(250) default NULL,

		  `employer2_supervisor` varchar(100) default NULL,

		  `employer2_from_date` varchar(60) default NULL,

		  `employer2_to_date` varchar(60) default NULL,

		  `employer2_leave_reason` text,

		  `employer2_country` varchar(100) default NULL,

		  `employer2_state` varchar(100) default NULL,

		  `employer2_county` varchar(100) default NULL,

		  `employer2_city` varchar(100) default NULL,

		  `employer2_zip` varchar(60) default NULL,

		  `employer2_address` varchar(150) default NULL,

		  `employer2_phone` varchar(60) default NULL,

		  `employer3` varchar(250) default NULL,

		  `employer3_position` varchar(150) default NULL,

		  `employer3_resp` text,

		  `employer3_pay_upon_leaving` varchar(250) default NULL,

		  `employer3_supervisor` varchar(100) default NULL,

		  `employer3_from_date` varchar(60) default NULL,

		  `employer3_to_date` varchar(60) default NULL,

		  `employer3_leave_reason` text,

		  `employer3_country` varchar(100) default NULL,

		  `employer3_state` varchar(100) default NULL,

		  `employer3_county` varchar(100) default NULL,

		  `employer3_city` varchar(100) default NULL,

		  `employer3_zip` varchar(60) default NULL,

		  `employer3_address` varchar(150) default NULL,

		  `employer3_phone` varchar(60) default NULL,

		  `language` varchar(50) default NULL,

		  `langugage_reading` varchar(20) default NULL,

		  `langugage_writing` varchar(20) default NULL,

		  `langugage_undarstanding` varchar(20) default NULL,

		  `langugage_where_learned` varchar(100) default NULL,

		  `language1` varchar(50) default NULL,

		  `langugage1_reading` varchar(20) default NULL,

		  `langugage1_writing` varchar(20) default NULL,

		  `langugage1_undarstanding` varchar(20) default NULL,

		  `langugage1_where_learned` varchar(100) default NULL,

		  `language2` varchar(50) default NULL,

		  `langugage2_reading` varchar(20) default NULL,

		  `langugage2_writing` varchar(20) default NULL,

		  `langugage2_undarstanding` varchar(20) default NULL,

		  `langugage2_where_learned` varchar(100) default NULL,

		  `language3` varchar(50) default NULL,

		  `langugage3_reading` varchar(20) default NULL,

		  `langugage3_writing` varchar(20) default NULL,

		  `langugage3_undarstanding` varchar(20) default NULL,

		  `langugage3_where_learned` varchar(100) default NULL,

		  `date_start` datetime default NULL,

		  `desired_salary` int(11) default NULL,

		  `can_work` varchar(250) default NULL,

		  `available` varchar(250) default NULL,

		  `unalailable` varchar(250) default NULL,

		  `total_experience` varchar(50) default NULL,

		  `skills` text,

		  `driving_license` tinyint(1) default NULL,

		  `license_no` varchar(100) default NULL,

		  `license_country` varchar(50) default NULL,

		  `reference` varchar(50) default NULL,

		  `reference_name` varchar(50) default NULL,

		  `reference_country` varchar(50) default NULL,

		  `reference_state` varchar(50) default NULL,

		  `reference_county` varchar(50) default NULL,

		  `reference_city` varchar(50) default NULL,

		  `reference_zipcode` varchar(20) default NULL,

		  `reference_address` varchar(150) default NULL,

		  `reference_phone` varchar(50) default NULL,

		  `reference_relation` varchar(50) default NULL,

		  `reference_years` varchar(10) default NULL,

		  `reference1` varchar(50) default NULL,

		  `reference1_name` varchar(50) default NULL,

		  `reference1_country` varchar(50) default NULL,

		  `reference1_state` varchar(50) default NULL,

		  `reference1_county` varchar(50) default NULL,

		  `reference1_city` varchar(50) default NULL,

		  `reference1_address` varchar(150) default NULL,

		  `reference1_phone` varchar(50) default NULL,

		  `reference1_relation` varchar(50) default NULL,

		  `reference1_years` varchar(10) default NULL,

		  `reference2` varchar(50) default NULL,

		  `reference2_name` varchar(50) default NULL,

		  `reference2_country` varchar(50) default NULL,

		  `reference2_state` varchar(50) default NULL,

		  `reference2_county` varchar(50) default NULL,

		  `reference2_city` varchar(50) default NULL,

		  `reference2_address` varchar(150) default NULL,

		  `reference2_phone` varchar(50) default NULL,

		  `reference2_relation` varchar(50) default NULL,

		  `reference2_years` varchar(10) default NULL,

		  `reference3` varchar(50) default NULL,

		  `reference3_name` varchar(50) default NULL,

		  `reference3_country` varchar(50) default NULL,

		  `reference3_state` varchar(50) default NULL,

		  `reference3_county` varchar(50) default NULL,

		  `reference3_city` varchar(50) default NULL,

		  `reference3_address` varchar(150) default NULL,

		  `reference3_phone` varchar(50) default NULL,

		  `reference3_relation` varchar(50) default NULL,

		  `reference3_years` varchar(10) default NULL,

		  `address1_country` varchar(100) default NULL,

		  `address1_state` varchar(60) default NULL,

		  `address1_county` varchar(100) default NULL,

		  `address1_city` varchar(100) default NULL,

		  `address1_zipcode` varchar(60) default NULL,

		  `address1` text,

		  `address2_country` varchar(100) default NULL,

		  `address2_state` varchar(60) default NULL,

		  `address2_county` varchar(100) default NULL,

		  `address2_city` varchar(100) default NULL,

		  `address2_zipcode` varchar(60) default NULL,

		  `address2` text,

		  `reference1_zipcode` varchar(20) default NULL,

		  `reference2_zipcode` varchar(20) default NULL,

		  `reference3_zipcode` varchar(20) default NULL,

		  PRIMARY KEY  (`id`)
	) TYPE=MyISAM;

