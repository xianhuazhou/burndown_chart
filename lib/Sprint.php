<?php
class Sprint {
	private $name = null;
	private $projectId = null;
	private $startDate = null;
	private $endDate = null;
	private $pdo = null;
	private $hours = 0;

	public function __construct($projectId, $name, $startDate, $endDate, $hours) {
		$this->projectId = $projectId;
		$this->name = $name;
		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$this->hours = $hours;
		$this->pdo = DB::getPDO();
	}

	public function save() {
		$stmt = $this->pdo->prepare("INSERT INTO sprints(project_id, name, start_date, end_date, hours, created_at) 
			VALUES(?, ?, ?, ?, ?, ?)");
		$stmt->execute(array($this->projectId, $this->name, $this->startDate, $this->endDate, $this->hours, date('Y-m-d H:i:s')));
        return $this->pdo->lastInsertId();
	}

	public static function getSprintById($id) {
		$stmt = DB::getPDO()->prepare("SELECT * FROM sprints WHERE id = ?");
		$stmt->execute(array($id));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		return $row;
	}

	public static function getSprints() {
		$stmt = DB::getPDO()->prepare("SELECT * FROM sprints");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getDays($startDate, $endDate) {
		$dayTime = strtotime($startDate);
		$endDayTime = strtotime($endDate);
		$days = array($startDate);
		while (true) {
			$dayTime += 86400;
			if ($dayTime >= $endDayTime) {
				break;
			}

			$days[] = date('Y-m-d', $dayTime);
		}
		$days[] = $endDate;

		return $days;
	}
}
