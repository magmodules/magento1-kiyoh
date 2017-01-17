<?php
/**
 * Magmodules.eu - http://www.magmodules.eu
 *
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category      Magmodules
 * @package       Magmodules_Kiyoh
 * @author        Magmodules <info@magmodules.eu)
 * @copyright     Copyright (c) 2017 (http://www.magmodules.eu)
 * @license       http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
$installer->startSetup();
$installer->run(
    "
	DROP TABLE IF EXISTS {$this->getTable('kiyoh_reviews')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('kiyoh_reviews')} (
	  `review_id` int(10) NOT NULL AUTO_INCREMENT,
	  `shop_id` int(5) NOT NULL,
	  `company` varchar(255) DEFAULT NULL,
	  `kiyoh_id` int(5) NOT NULL,
	  `score` smallint(6) DEFAULT '0',
	  `score_q2` smallint(6) DEFAULT '0',
	  `score_q3` smallint(6) DEFAULT '0',
	  `score_q4` smallint(6) DEFAULT '0',
	  `score_q5` smallint(6) DEFAULT '0',
	  `score_q6` smallint(6) DEFAULT '0',
	  `score_q7` smallint(6) DEFAULT '0',
	  `score_q8` smallint(6) DEFAULT '0',
	  `score_q9` smallint(6) DEFAULT '0',
	  `score_q10` smallint(6) DEFAULT '0',
	  `customer_name` varchar(255) DEFAULT NULL,
	  `customer_email` varchar(255) DEFAULT NULL,
	  `customer_place` varchar(255) DEFAULT NULL,
	  `recommendation` tinyint(1) DEFAULT NULL,
	  `positive` text,
	  `negative` text,
	  `purchase` text,
	  `reaction` text,
	  `date_created` date NOT NULL,
	  `date_updated` date NOT NULL,
	  `sidebar` tinyint(1) NOT NULL DEFAULT '1',
	  `status` tinyint(5) NOT NULL DEFAULT '1',
	  PRIMARY KEY (`review_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

	DROP TABLE IF EXISTS {$this->getTable('kiyoh_log')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('kiyoh_log')} (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`type` varchar(255) NOT NULL,
		`shop_id` varchar(255) NOT NULL,
		`company` varchar(255) DEFAULT NULL,
		`review_update` int(5) DEFAULT '0',
		`review_new` int(5) DEFAULT '0',
		`response` text,
		`order_id` int(10) DEFAULT NULL,
		`cron` varchar(255) DEFAULT NULL,
		`date` datetime NOT NULL,
		`time` varchar(255) NOT NULL,
		`api_url` text,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

	DROP TABLE IF EXISTS {$this->getTable('kiyoh_stats')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('kiyoh_stats')} (
	  `id` int(5) NOT NULL AUTO_INCREMENT,
	  `company` varchar(255) DEFAULT NULL,
	  `shop_id` int(5) NOT NULL,
	  `score` smallint(6) DEFAULT '0',
	  `score_q2` smallint(6) DEFAULT '0',
	  `score_q2_title` varchar(255) NOT NULL,
	  `score_q3` smallint(6) DEFAULT '0',
	  `score_q3_title` varchar(255) NOT NULL,
	  `score_q4` smallint(6) DEFAULT '0',
	  `score_q4_title` varchar(255) NOT NULL,
	  `score_q5` smallint(6) DEFAULT '0',
	  `score_q5_title` varchar(255) NOT NULL,
	  `score_q6` smallint(6) DEFAULT '0',
	  `score_q6_title` varchar(255) NOT NULL,
	  `score_q7` smallint(6) DEFAULT '0',
	  `score_q7_title` varchar(255) NOT NULL,
	  `score_q8` smallint(6) DEFAULT '0',
	  `score_q8_title` varchar(255) NOT NULL,
	  `score_q9` smallint(6) DEFAULT '0',
	  `score_q9_title` varchar(255) NOT NULL,
	  `score_q10` smallint(6) DEFAULT '0',
	  `score_q10_title` varchar(255) NOT NULL,
	  `scoremax` smallint(6) DEFAULT '0',
	  `votes` int(5) DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
"
);
$installer->endSetup(); 