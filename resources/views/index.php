<?php 

//url
$url = getenv('URL');

//user data set on login
$id = $_SESSION['id'];
$name = $_SESSION['name'];

//prepare for get requests from api
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

//function to grab data from api
function cURLwithURL($apiURL, $url, $id, $ch) {
	curl_setopt($ch, CURLOPT_URL, $url . $apiURL . $id);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo "Error: " . curl_error($ch);
	}
	return json_decode(trim($result), true);
}

//grab lift data
$lifts = cURLwithURL('api/lifts/', $url, $id, $ch);
$_SESSION['userLifts'] = $lifts;

//grab bodyweight data
$bodyweights = cURLwithURL('api/bodyweights/', $url, $id, $ch);
$_SESSION['userBodyweights'] = $bodyweights;

//grab lifttypes
$lifttypes = cURLwithURL('api/lifttypes/', $url, $id, $ch);

//grab food data
$foodhistory = cURLwithURL('api/foods/', $url, $id, $ch);

//build arrays with the GET data to make graphs
$liftxaxis = array();
$liftyaxis = array();
$types = array();

if (count($lifts) > 0) {
	foreach ($lifts as $lift) {
		$liftxaxis[] = date("n/j", strtotime($lift["date"]));

		$onerepmax = $lift['weight'] * (1 + ($lift['reps']/30));
		$liftyaxis[] = $onerepmax;

		$types[] = $lift["type"];
	}
}

//bodyweight data
$weightxaxis = array();
$weightyaxis = array();

if (count($bodyweights) > 0) {
	foreach ($bodyweights as $bodyweight) {
		$date = strtotime($bodyweight["date"]);
		$weightxaxis[] = date("m-d", $date);
		$weightyaxis[] = $bodyweight["weight"];
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="../resources/css/index.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:200,300,400,500" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<title>LiftApp Site</title>
		
	</head>
	<body>
		<div id="topContainer">
			<div id="headerContainer">
				<h1 align="center" id = "mainTitle">Dashboard</h1>
				<img src="../resources/images/hugeIcon.png" height="62" width="62" id="icon">

			</div>	
			<div id="abouttheapp">
			<a href="https://github.com/austinbailey1114/LiftAppSite" id="aboutLink"><h3>About</h3></a>
			<a href="https://github.com/austinbailey1114/iOS" id="appLink"><h3>The App</h3></a>
			</div>
			<div id="linksContainer">
				<div class="dropdown">
					<button onclick="showDropDown()" class="dropButton"> <?php echo $name ?></button>
					<div id="dropDownElements" class="dropDownContent">
						<a href="./logout"><h3 id="accountLink">Log Out</h3></a>
						<a href="./settings.php"><h3 id="passwordLink">Settings</h3></a>
					</div>
				</div>
				<img src="../resources/images/userIcon.png" height="52" width="52" id="userIcon">
			</div>
		</div>
		<div id="dashboardDiv">
			<div id="container">
				<div class="lift">
					<div>
						<h2 align="center" id="liftProgressTitle">Your Lift Progress</h2>
						<form id="lifttableform" action="lifts/view/asTable">
							<a id="lifttable" href="lifts/view/asTable">View as Table</a>
						</form>
					</div>
						<select name="chooseLift" id="chooseLiftToDisplay" onchange="buildliftChart()">
						<?php

							if (count($lifttypes) > 0) {
								foreach ($lifttypes as $lifttype) {
									$typestring = str_replace('_', ' ', $lifttype['name']);
									echo '<option value="'.$typestring.'">'.$typestring.'</option>';
								}
							} else {
								echo "<option value='null'>No Types Yet</option>";
							}
						?>
						</select>
					<div id ="graphDiv">
						<canvas id="myChart"></canvas>
					</div>
				</div>
				<div id="newLiftContainer">
					<form action="./lifts/addLift" method="post">
						<div id="addNewWeight">
							<p id="promptWeight">Weight: </p>
							<input type="text" name="weight" id="weightInput" placeholder="pounds" autocomplete="off">
						</div>
						<div id="addNewReps">
							<p id="promptReps">Reps:</p>
							<input type="text" name="reps" id="repsInput" placeholder="repetitions" autocomplete="off">
						</div>
						<div id="addNewType">
							<p id="promptType">Type:</p>
							<div id="typeSelectDiv">
								<select id="lifttypes" name='lifttypes' onchange="fillType()">
								<?php
									if (count($lifttypes) > 0) {
										$typeOptions = "<option value='null'>Select Below</option>";
										$typeOptions = $typeOptions . "<option value='addnew'>Add New</option>";
										foreach ($lifttypes as $lifttype) {
											$typestring = str_replace('_', ' ', $lifttype['name']);
											$typeOptions = $typeOptions . '<option value="'.$typestring.'">'.$typestring.'</option>';
										}
									} else {
										$typeOptions = "<option value='null'>Select Below</option>";
										$typeOptions = $typeOptions . "<option value='addnew'>Add New</option>";
									}
									echo $typeOptions;

									
								?>
							</select>
							</div>
						</div>
						<div id="addNewDate">
							<p id="promptDate">Date:</p>
							<script type="text/javascript">
								jQuery(function($){
								   $("#dateInput").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
								   
								});
							</script>
							<input type="test" name="date" id="dateInput" placeholder="leave blank if today" autocomplete="off">
						</div>
						<button id="saveLift">Save</button>
					</form>
				</div>
			</div>
			<div class="nutrition">
				<div id="showData">
					<?php
						$calories = 0;
						$fat = 0;
						$carbs = 0;
						$protein = 0;
						if (count($foodhistory) > 0) {
							foreach ($foodhistory as $food) {
								# code...
								$calories += $food['calories'];
								$fat += $food['fat'];
								$carbs += $food['carbs'];
								$protein += $food['protein'];
							}
						}

						echo "<p id='displayCals'>Today's calories: ".$calories."</p>";
						echo "<p id='displayFat'>Today's fat: ".$fat."g</p>";
						echo "<p id='displayCarbs'>Today's carbs: ".$carbs."g</p>";
						echo "<p id='displayProtein'>Today's protein: ".$protein."g</p>";
					?>
				</div>
				<div id="newFood">
					<form id="searchFood" action="./search.php" method="post">
						<div id="enterfood">
							<h2 id="newFoodTitle">Search Foods: </h2>
							<input type="text" name="searchField" id="searchInput" placeholder="Food, brand, etc.">
						</div>
						<div id="foodbutton">
							<button id="search">Search</button>
						</div>
					</form>
				</div>
				<div id="foodHistoryContainer">
					<h3>Food Today</h3>
					<div id="foodHistory">
						<?php
							if(count($foodhistory) > 0) {
								foreach ($foodhistory as $food) {
									# code...
									echo "<p>" . $food['name'] . "</p>";
								}
							}
							else {
								echo "<p>No foods to display<p>";
							}
						?>
					</div>
				</div>
			</div>
			<div id="weightDiv">
				<div class="bodyweightGraph">
					<h2 id="bodyweighttabletitle">Bodyweight</h2>
					<a id="bodyweighttable" href="bodyweights/view/asTable">View as Table</a>
					<div id ="bodyweightGraphDiv">
						<canvas id="bodyweightChart"></canvas>
					</div>
				</div>
				<div id="newWeight">
					<form action="./bodyweights/addBodyweight" method="post">
						<div id="promptBodyweight">
							<h2 id="weightTitle">Update: </h2>
							<input type="text" name="updateWeight" id="newBodyWeight" placeholder="pounds">
						</div>
						<div id="addBodyweightButtonDiv">
							<button id="add">Update</button>
						</div>
					</form>
				</div>
				
			</div>
		</div>
	</body>
	<script type="text/javascript">
		//convert php arrays to javascript arrays
		var liftxaxis= <?php echo json_encode($liftxaxis); ?>;
		var liftyaxis= <?php echo json_encode($liftyaxis); ?>;
		var types = <?php echo json_encode($types); ?>;
		var weightxaxis = <?php echo json_encode($weightxaxis); ?>;
		var weightyaxis = <?php echo json_encode($weightyaxis); ?>;
		var typeOptions = <?php echo json_encode($typeOptions); ?>;

		<?php
			if(isset($_SESSION['message'])) {
				$message = $_SESSION['message'];
				?> 
					var message = <?php echo json_encode($message); ?>;
					if (message == "success") {
						swal('Item added successfully', '', 'success');
					}
					else if (message == "failed") {
						swal("Unable to add item. Please make sure your inputs are numbers", "", "warning");
					}
					else if(message == "deleteSuccess") {
						swal("Item deleted successfully", " ", "success");
					}
					else if(message == "deleteFailed") {
						swal("Failed to delete item", "", 'warning');
					}

				
				<?php
				unset($_SESSION['message']);
			} 

			if(isset($_GET["lift"])) {
				?>
					var liftGraphSelect = document.getElementById('chooseLiftToDisplay');
					liftGraphSelect.value = <?php echo json_encode($_GET['lift']); ?>;
					liftGraphSelect.text = <?php echo json_encode($_GET['lift']); ?>;
					var typeSelect = document.getElementById('lifttypes');
					typeSelect.value = <?php echo json_encode($_GET['lift']); ?>;
					typeSelect.text = <?php echo json_encode($_GET['lift']); ?>;

				<?php
			} else {
				//lift isnt set
			}
		?>

		function fillType() {
			try {
				var type = document.getElementById("lifttypes");
    			var choice = type.options[type.selectedIndex].text;
    			if (choice == 'Add New') {
    				var selectDiv = document.getElementById('typeSelectDiv');
    				selectDiv.innerHTML = "<button id='tempButton' type=button onclick='unfillType()'><img src='../resources/images/xicon.png' height='15' width='15' style='margin-right: 5px;'></button><input type='text' name='type' id='typeInput' placeholder='new type' autocomplete='off'>";
    				document.getElementById('typeInput').focus();
    			}
			}
			catch(err) {
				//document.getElementById("typeInput").value = "No Types";
			}
			
		}

		function unfillType() {
			var selectDiv = document.getElementById('typeSelectDiv');
			selectDiv.innerHTML = "<select id='lifttypes' name='lifttypes' onchange='fillType()'>"+ typeOptions + "</select>";
		}

		function checkInput(value, pid, reset) {
			if (isNaN(value)) {
				var prompt = document.getElementById(pid);
				prompt.innerHTML = "<img src='./images/warning.png' height='20' width='20' style='margin-right: 5px;'>Invalid Input";
			} else {
				var prompt = document.getElementById(pid);
				prompt.textContent = reset;
			}
		}

		var repsInput = document.getElementById('repsInput');
		var weightInput = document.getElementById('weightInput');
		var bodyweightInput = document.getElementById('newBodyWeight');
		repsInput.addEventListener('input', function() { checkInput(repsInput.value, 'promptReps', 'Reps: '); }, false);
		weightInput.addEventListener('input', function() { checkInput(weightInput.value, 'promptWeight', 'Weight: '); }, false);
		bodyweightInput.addEventListener('input', function() { checkInput(bodyweightInput.value, 'weightTitle', 'Update: ')}, false);

		fillType();

		//show the drop down on click
		function showDropDown() {
			document.getElementById('dropDownElements').classList.toggle('show');
		}

		//hide the dropdown when click is anywhere besides dropButton
		window.onclick = function(event) {
			if (!event.target.matches('.dropButton')) {
				document.getElementById('dropDownElements').classList.remove('show');
			}
		}

	</script>
	<script type="text/javascript" src="../resources/js/buildgraph.js"></script>
</html>