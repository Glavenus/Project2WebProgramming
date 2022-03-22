

<?php
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);
	
	
	function getLeaderboard($leaderboardFile) {
		$file = fopen($leaderboardFile, 'r');
		$leaderBoard = array();
		while(!feof($file)) {
			$items = explode(",", fgets($file));
			if ($items[0] != 'usr' and count($items) == 4) {
				$leaderBoard[$items[0]] = array("win" => $items[1], "loss" => $items[2], "password" => $items[3], "name"=> $items[0]);
			}
		}
		fclose($file);
		return $leaderBoard;
	}
	
	function displayLeaderboard($leaderBoard) {
		//$leaderBoard = getLeaderboard($leaderboardFile);
		$scores = array_column($leaderBoard, "win");
		$rank = 1;
		echo "<table>
			<tr>
				<th>Rank</th>
				<th>Name</th>
				<th>Wins</th>
				<th>Losses</th>
			<tr>";
			
		array_multisort($scores, SORT_DESC, $leaderBoard);
		foreach ($leaderBoard as $user) {
			echo "
			<tr>
				<td>".$rank."</td>
				<td>".$user["name"]."</td>
				<td>".$user["win"]."</td>
				<td>".$user["loss"]."</td>
			<tr>
			";
			//echo "Rank: ".$rank." ".$user["name"]." Wins: ".$user["win"]." Losses: ".$user["loss"]."<br>";
			$rank++;
		}
		
		echo "</table>";	
	}
	
	function validateUser($userName, $leaderboard) {
		if(isset($leaderboard[$userName])) {
			return "true";
		}
		return "false";
	}
	
	function addUser($userName, $password, &$leaderboard) {
		// If user is not already on the board add. Then return the user's info. 
		if (!isset($leaderboard[$userName])) {
			$leaderboard[$userName] = array("win" => 0, "loss" => 0, "password" => $password, "name"=> $userName);
		}
		//var_dump($leaderboard);
	}
	
	function saveUserScores($leaderboard, $leaderboardFile) {
		//var_dump($leaderboard);
		$info = "usr,win,loss,password";
		foreach ($leaderboard as $user) {
			$userInfo = "\n".$user['name'].",".$user['win'].",".$user['loss'].",".$user['password'];
			$info .= $userInfo;			
		}
		//echo "helloooo  ".$info;
		$file = fopen($leaderboardFile, 'w');
		fwrite($file, $info);
		fclose($file);
	}		
	
	function updateUserWin($userName, &$leaderboard) {
		$value = (int)($leaderboard[$userName]['win']);
		$leaderboard[$userName]['win'] = (string)(++$value);

	}
	
	function updateUserLoss($userName, &$leaderboard) {
		$value = (int)($leaderboard[$userName]['loss']);
		$leaderboard[$userName]['loss'] = (string)($value++);
	}

	//Global for convenience. 
	$scoreFile = "usrs.txt";

	/*testing html
	
	<html>

	<head>

		<title> Usr save Page </title>
		<style>
			table, th, td {
				border:1px solid black;
			}
		</style>
	</head>

	<body>
		<h1>User save<h1>
		<?php
		
			addUser("Beep", "sheep", $board);
			updateUserWin("Beep", $board);
			updateUserWin("Beep", $board);
			displayLeaderboard($board);
			saveUserScores($board, $scoreFile);
			
			//
			
			//echo (string)(validateUser("Beep", $board));
			//saveUserScores($board, $ufile);

		?>

	</body>	
	</html>
	*/
?>


