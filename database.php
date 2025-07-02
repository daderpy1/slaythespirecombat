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
    echo "Connected successfully";

    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            case 'addRecord':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 3) ) {
                   $aResult['error'] = 'Error in arguments!';
               }
               else {
		   $sql = "INSERT INTO player (name, wins, losses) VALUES ('{$_POST['arguments'][0]}', '{$_POST['arguments'][1]}', '{$_POST['arguments'][2]}')";

                   if (mysqli_query($connection, $sql)) {
			$aResult['result'] = "added entry";
		   } else {
			$aResult['result'] = "error: " . $sql . "<br>" . mysqli_error($connection);
		   }
               }
               break;

            default:
               $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
               break;
        }

    }

    $aResult = array();

    echo json_encode($aResult);
    mysqli_close($connection);
?>
