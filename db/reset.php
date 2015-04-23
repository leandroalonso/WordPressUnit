<?php

define('TEST_ENVIRONMENT', true);
define( 'WP_INSTALLING', true );
require_once('../../../wp-load.php' );
require_once('../../../wp-admin/includes/upgrade.php');

class Reset {
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->clearDatabase();
		$this->createWPDatabase();
		$this->populate();
		$this->activatePlugin();
	}

	public function clearDatabase() {
		$this->db = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASSWORD);
		$this->db->query("DROP DATABASE " . DB_NAME);
		$this->db->query("CREATE DATABASE " . DB_NAME);
	}

	public function createWPDatabase() {
		$result = wp_install('Test Environment', 'test', 'test@test.com', false, '', wp_slash( 'teste' ), 'en' );
	}

	public function populate() {
		$seeds = file_get_contents(dirname(__FILE__) . '/data/seed.sql');
		$this->db->query("USE ". DB_NAME);
		$this->db->query($seeds);
	}

	public function activatePlugin() {
		activate_plugin('WordPressUnit/wp-unit.php');
		$seeds = file_get_contents(dirname(__FILE__) . '/data/tm.sql');
		dbDelta($seeds);

	}
}

new Reset();