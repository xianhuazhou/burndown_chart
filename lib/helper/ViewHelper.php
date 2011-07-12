<?php
function get_project_name_by_project_id($projects, $project_id) {
	foreach ($projects as $project) {
		if ($project['id'] == $project_id) {
			return $project['name'];
		}
	}

	return '';
}
