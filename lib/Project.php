<?php
class Project {
	private $name = null;
	private $pdo = null;

	public function __construct($name) {
		$this->name = $name;
		$this->pdo = DB::getPDO();
	}

	public function save() {
		$stmt = $this->pdo->prepare("INSERT INTO projects(name, created_at) VALUES(?, ?)");
		$stmt->execute(array($this->name, date('Y-m-d H:i:s')));
	}

	public function getProjects() {
		$stmt = DB::getPDO()->prepare("SELECT * FROM projects");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
