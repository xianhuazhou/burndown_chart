<?php
class DB {
	private static $pdo = null;

	public static function getPDO() {
		if (self::$pdo) {
			return self::$pdo;
		}

		return self::$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	}
}
