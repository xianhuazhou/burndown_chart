<?php
class Burndown {
	private $sprintId = null;
	private $burnedHours = 0;
	private $day = null;
	private $pdo = null;

	public function __construct($sprintId, $day, $burnedHours) {
		$this->pdo = DB::getPDO();
		$this->sprintId = $sprintId;
		$this->burnedHours = $burnedHours;
		$this->day = $day;
	}

	public function save() {
		$stmt = $this->pdo->prepare("SELECT remaining_hours FROM burndown WHERE sprint_id = ? ORDER BY day DESC LIMIT 1");
		$stmt->execute(array($this->sprintId));
		$row = $stmt->fetch(PDO::FETCH_NUM);

		if (!isset($row[0])) {
			$sprint = Sprint::getSprintById($this->sprintId);
			$remainingHours = $sprint['hours'];
		} else {
			$remainingHours = $row[0];
		}
		$stmt->closeCursor();

		$hours = $remainingHours - $this->burnedHours;

		$stmt = $this->pdo->prepare("INSERT INTO burndown(sprint_id, day, remaining_hours) VALUES(?, ?, ?)");
		$stmt->execute(array($this->sprintId, $this->day, $hours));
	}

	public static function getRowsBySprintId($sprintId) {
		$stmt = DB::getPDO()->prepare("SELECT day, remaining_hours FROM burndown WHERE sprint_id = ?");
		$stmt->execute(array($sprintId));
		$rows = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$rows[$row['day']] = (double)$row['remaining_hours'];
		}
		$stmt->closeCursor();

		return $rows;
	}
}
