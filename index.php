<html>
    <head>
	<title>Rock Paper Scissors</title>
	<link rel="icon" type="images/x-icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Rock-paper-scissors.svg/375px-Rock-paper-scissors.svg.png">
	<script src="jquery-3.7.1.min.js"></script>
    </head>

    <body style="background-color:cornflowerblue; text-align:center;">
        <h1>Rock Paper Scissors!</h1>
    </body>

    <body>
	<h2>Choose:</h2>
	<button id="rockB"><img src="https://pngimg.com/uploads/stone/stone_PNG13545.png" alt="Rock" style="width:100px;height:100px;"></button>
	<button id="paperB"><img src="https://pngimg.com/uploads/paper_sheet/paper_sheet_PNG7227.png" alt="Paper" style="width:100px;height:100px;"></button>
	<button id="scissorsB"><img src="https://pngimg.com/uploads/scissors/scissors_PNG2.png" alt="Scissors" style="width:100px;height:100px;"></button>
    </body>

    <body>
	<p id="resultText"></p>
    </body>

    <body>
	<h3 id="statText"></h3>
    </body>

    <div>
	<label for="nameInput">Name:</label>
	<input type="text" id="nameInput"><br><br>
	<button id="recordB">Record Score</button>
    </div>

    <script>
	const rockB = document.getElementById('rockB');
	const paperB = document.getElementById('paperB');
	const scissorsB = document.getElementById('scissorsB');
	const resultText = document.getElementById('resultText');
	const statText = document.getElementById('statText');
	const table = document.getElementById('playerTable');
	const nameInput = document.getElementById('nameInput');
	const recordB = document.getElementById('recordB');

	var option = 0; //1 = rock, 2 = paper, 3 = scissors
	var compOption = 0;
	var playerWins = 0;
	var compWins = 0;

	rockB.addEventListener('click', function() {
	    option = 1;
	    decideWinner();
	})

	paperB.addEventListener('click', function() {
            option = 2;
	    decideWinner();
        })

	scissorsB.addEventListener('click', function() {
            option = 3;
	    decideWinner();
        })

	function decideWinner() {
	    compOption = parseInt((Math.random()*3)+1);
	    var winner = 0; //0 = player, 1 = cpu, 2 = draw
	    
	    if (option == compOption) {
		winner = 2;
	    } else if (option-1 == compOption || option == 1 && compOption == 3) {
		winner = 0;
		playerWins++;
	    } else {
		winner = 1;
		compWins++;
	    }
	    
	    updatePage(winner)
	}

	function updatePage(winner) {
	    var resultString = "";
	    if (option == 1) {
		resultString += "Player chose rock. ";
	    } else if (option == 2) {
                resultString += "Player chose paper. ";
            } else {
                resultString += "Player chose scissors. ";
            }

	    if (compOption == 1) {
                resultString += "Computer chose rock. ";
            } else if (compOption == 2) {
                resultString += "Computer chose paper. ";
            } else {
                resultString += "Computer chose scissors. ";
            }

	    if (winner == 2) {
		resultString += "Draw!";
	    } else if (winner == 0) {
		resultString += "Player Won!";
	    } else {
		resultString += "Computer Won!";
	    }

	    resultText.textContent = resultString;
	    statText.textContent = "Player Wins: " + playerWins + "	Computer Wins: " + compWins;
	}
    </script>

    <table id='entryTable'>
	<td><h3>Name  </h3></td>
	<td><h3>Wins  </h3></td>
	<td><h3>Losses  </h3></td>

    <script>
	const entryTable = document.getElementById('entryTable');

	function newRow(name, wins, losses) {
	    const entry = document.createElement("tr");
	    const tdName = document.createElement("td");
	    const tdWins = document.createElement("td");
	    const tdLosses = document.createElement("td");
	    
	    tdName.textContent = name;
	    tdWins.textContent = wins;
	    tdLosses.textContent = losses;

	    entry.appendChild(tdName);
	    entry.appendChild(tdWins);
	    entry.appendChild(tdLosses);
	    entryTable.appendChild(entry);
	}

	function makeRecord() {
	    const nameValue = nameInput.value;
	    console.log(nameValue);
	    console.log(playerWins);
	    console.log(compWins);

	    jQuery.ajax({
	    	type: "POST",
	    	url: 'database.php',
	    	dataType: 'json',
	    	data: {functionname: 'addRecord', arguments: [nameValue, playerWins, compWins]},

	    	success: function (obj, textstatus) {
	            if( !('error' in obj) ) {
	                console.log(obj.result);
	            } else {
	                console.log(obj.error);
	            }
	        }
	    });
	
	    newRow(nameValue, playerWins, compWins);
	}

	recordB.addEventListener("click", makeRecord, false);
    </script>


    <?php
	ini_set('display_errors', 1);
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        $servername = "localhost";
        $username = "admin";
        $password = "password";
        $db = "rpsDatabase";
    
        $connection = mysqli_connect($servername, $username, $password, $db);

        if ($connection->connect_error) {
    	    die("Connection failed: " . mysqli_connect_error());
        }

	$query = "SELECT * FROM player";
	$result = mysqli_query($connection, $query);

	while($row = mysqli_fetch_array($result)){
	    echo "<tr><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['wins']) . "</td><td>" . htmlspecialchars($row["losses"]) . "</td></tr>";
        }

	mysqli_close($connection);
    ?>

	</table>

</html>
