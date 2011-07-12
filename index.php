<?php 
require_once 'config/config.php';
$action = isset($_GET['action']) ? $_GET['action'] : null;
$sprintId = isset($_GET['sprint_id']) ? $_GET['sprint_id'] : null;
$thisPage = $_SERVER['PHP_SELF'];
switch ($action) {
case 'create_project':
	$project = new Project($_POST['name']);
	$project->save();
	break;

case 'create_sprint':
	$sprint = new Sprint($_POST['project_id'], $_POST['name'], $_POST['start_date'], $_POST['end_date'], $_POST['hours']);
	$sprint->save();
	break;

case 'create_burndown':
	$burndown = new Burndown($_POST['sprint_id'], $_POST['day'], $_POST['burned_hours']);
	$burndown->save();
	header('Location: ' . $thisPage . '?sprint_id=' . $_POST['sprint_id']);
	exit;
}

if ($action) {
	header('Location: ' . $thisPage);
	exit;
}

$projects = Project::getProjects();
$sprints = Sprint::getSprints();

if ($sprintId) {
	$sprint = Sprint::getSprintById($sprintId);
	$days = Sprint::getDays($sprint['start_date'], $sprint['end_date']);
	$burndownItems = Burndown::getRowsBySprintId($sprintId);

	$hours = array();
	foreach ($days as $day) {
		if (isset($burndownItems[$day])) {
			$hours[] = $burndownItems[$day];
		} else {
			$hours[] = null;
		}
	}

	foreach ($days as $k => $day) {
		$days[$k] = substr($day, 5);
	}
}
?>
<!DOCTYPE html >
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Sprint burndown charts</title>
    <script src="javascripts/RGraph.common.core.js" ></script>
    <script src="javascripts/RGraph.common.context.js" ></script>
    <script src="javascripts/RGraph.line.js" ></script>
    <script src="javascripts/app.js" ></script>
    <!--[if IE 8]><script src="javascripts/excanvas/excanvas.original.js"></script><![endif]-->
    <?php if ($sprintId): ?>
    <script>
	window.onload = function() {
		drawChart({
			hours: <?php echo json_encode($hours) ?>,
				labels: <?php echo json_encode($days) ?>,
				title: '<?php echo get_project_name_by_project_id($projects, $sprint['project_id']) . ': ' . $sprint['name'] ?>',
				colors: ['gray', 'green']
		}).Draw();
	};
    </script>
    <?php endif; ?>
    <style type="text/css">
        body {
            margin: 5px;
        }
        #chart {
		   margin: 10px;
		}
    </style>
</head>
<body>

    <form method="post" action="<?php echo $thisPage ?>?action=create_project">
		<fieldset>
	       <legend>Create a new project:</legend>
		   name: <input type="text" name="name">&nbsp;<input type="submit" value="Create Project">
       </fieldset>
    </form>

    <?php if ($projects): ?>
		<form method="post" action="<?php echo $thisPage ?>?action=create_sprint">
			<fieldset>
				   <legend>Start a new Sprint:</legend>
				   Project: <select size="1" name="project_id">
				   <?php foreach ($projects as $project): ?>
					   <option value="<?php echo $project['name'] ?>"><?php echo $project['name'] ?></option>
				   <?php endforeach; ?>
				   </select><br>
				   Name: <input type="text" name="name"><br>
				   Hours: <input type="text" name="hours"><br>
				   Date Range: <input type="text" name="start_date"> - <input type="text" name="end_date"><br>
				  <input type="submit" value="Create Sprint">
			</fieldset>
		</form>
    <?php endif; ?>

	<?php if ($sprints): ?>
		<form method="post" action="<?php echo $thisPage ?>?action=create_burndown">
			<fieldset>
			   <legend>Create a new burndown item:</legend>
			   Sprint: <select size="1" name="sprint_id" id="sprint_id">
			   <?php foreach ($sprints as $sprint): ?>
				   <option value="<?php echo $sprint['id'] ?>"><?php echo get_project_name_by_project_id($projects, $sprint['project_id']) . ' ' . $sprint['name'] ?></option>
			   <?php endforeach; ?>
			   </select> <input type="button" value="Show Burndown" onclick="var select = document.getElementById('sprint_id'); window.location.href='<?php echo $thisPage ?>?sprint_id=' + select.options[select.selectedIndex].value"><br>
			   Date: <input type="text" name="day"><br>
			   Burned hours: <input type="text" name="burned_hours"><br>
			  <input type="submit" value="Create burndown item">
			</fieldset>
		</form>
	<?php endif; ?>

  <div id="chart">
    <canvas id="g" width="1024" height="400"></canvas>
  </div>

   <div style="text-align: center"><a href="http://www.rgraph.net" target="_blank"> RGraph: HTML5 canvas graph library </a></div>
</body>
</html>
