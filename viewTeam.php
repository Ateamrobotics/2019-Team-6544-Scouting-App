<?php include('includes/database.php'); ?>
<?php
    $teamNumber = 0;
    if(isset($_GET['num'])) {
        $teamNumber = $_GET['num'];
    }

    $user = null;
    $scouter = null;

    if(isset($_GET['u'])) {
      $user = $_GET['u'];
      $scouter = $_GET['s'];
    }

    $query = "SELECT * FROM match_scout WHERE teamNumber = ".$teamNumber. " ORDER BY matchNumber";
    $query2 = "SELECT * FROM match_scout_1 WHERE teamNumber = ".$teamNumber. " ORDER BY matchNumber";
    $query3 = "SELECT * FROM match_scout_2 WHERE teamNumber = ".$teamNumber. " ORDER BY matchNumber";
    $query4 = "SELECT * FROM match_scout_3 WHERE teamNumber = ".$teamNumber. " ORDER BY matchNumber";
    $query5 = "SELECT * FROM match_scout_4 WHERE teamNumber = ".$teamNumber. " ORDER BY matchNumber";
    $query6 = "SELECT * FROM matches WHERE blueTeam1=".$teamNumber." OR blueTeam2=".$teamNumber." OR blueTeam3=".$teamNumber." OR redTeam1=".$teamNumber." OR redTeam2=".$teamNumber." OR redTeam3=".$teamNumber;
    $query7 = "SELECT * FROM robot_info WHERE teamNumber = ".$teamNumber;
    $query9 = "SELECT * FROM teleop_info WHERE teamNumber = ".$teamNumber;
    $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
    $result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
    $result3 = $mysqli->query($query3) or die($mysqli->error.__LINE__);
    $result4 = $mysqli->query($query4) or die($mysqli->error.__LINE__);
    $result5 = $mysqli->query($query5) or die($mysqli->error.__LINE__);
    $result6 = $mysqli->query($query6) or die($mysqli->error.__LINE__);
    $result7 = $mysqli->query($query7) or die($mysqli->error.__LINE__);
    $result9 = $mysqli->query($query9) or die($mysqli->error.__LINE__);

    $teamInfo = $mysqli->query("SELECT * FROM team_info WHERE teamNumber = ".$teamNumber)->fetch_object();
    $teamName = $teamInfo->teamName;
    $teamSchoolName = $teamInfo->teamSchoolName;
    $teamEmail = $teamInfo->teamEmail;
    $teamAge = $teamInfo->teamAge;
    $teamLocation = $teamInfo->teamLocation;

    $robotInfo = $mysqli->query("SELECT canClimb2, canClimb3 FROM robot_info WHERE teamNumber = ".$teamNumber)->fetch_object();
    if(isset($robotInfo->canClimb2)) {
      $canClimb2 = $robotInfo->canClimb2;
      $canClimb3 = $robotInfo->canClimb3;
    }
    else {
      $canClimb2 = null;
      $canClimb3 = null;
    }

    $autoInfo = $mysqli->query("SELECT * FROM auto_info WHERE teamNumber = ".$teamNumber)->fetch_object();
    if(isset($autoInfo->canCollectHatch)) {
      $canCollectHatch = $autoInfo->canCollectHatch;
      $canCollectCargo = $autoInfo->canCollectCargo;
      $autoExtraInformation = $autoInfo->extraInformation;
    }
    else {
      $canCollectHatch = null;
      $canCollectCargo = null;
      $autoExtraInformation = null;
    }

    $teleopInfo = $mysqli->query("SELECT * FROM teleop_info WHERE teamNumber = ".$teamNumber)->fetch_object();
    if(isset($teleopInfo->averageNumCargo)) {
      $averageNumHatches = $teleopInfo->averageNumHatches;
      $averageNumCargo = $teleopInfo->averageNumCargo;
      $speedClimb2 = $teleopInfo->speedClimb2;
      $speedClimb3 = $teleopInfo->speedClimb3;
      $teleopExtraInformation = $teleopInfo->extraInformation;
    }
    else {
      $averageNumHatches = null;
      $averageNumCargo =  null;
      $speedClimb2 = null;
      $speedClimb3 = null;
      $teleopExtraInformation = null;
    }
    

    /*
      $sidemenus = array();
      while ($sidemenu = mysql_fetch_object($autoInfo)) {
        $sidemenus[] = $sidemenu;
      }
    */
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="FIRSTicon_RGB_withTM.jpg">
    <title>A-Team Robotics Scouting Page</title>
    <!-- Bootstrap core CSS -->
    <link href="css/main.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/custom.css" rel="stylesheet">
    <style>
      tr, td {
        border: 1px solid #000000;
        padding: 5px;
      }
      th {
        border: 2px solid #000000;
        padding: 3px;
      }
      tr:nth-child(even) {
        background: #DDD
      }
      tr:nth-child(odd) {
        background: #FFF
      }
      table {
        border: 1px solid #000000;
        /*table-layout: auto;
        width: 1000px;*/
      }
    </style>
    <script src="setScores.js" type="text/javascript"></script>
    <script type="text/javascript">
      var mainScoutInformation = [
        <?php
            $output = "";
            $numResults = mysqli_num_rows($result);
            $counter = 0;
            while($row = $result->fetch_assoc()) {
                $counter += 1;
                $output .= '['.$row['matchNumber'].',';
                $output .= '"'.$row['startLocation'].'",';
                $output .= '"'.$row['climb'].'",';
                $output .= '"'.$row['climbLevel'].'",';
                $output .= '"'.$row['climbFail'].'",';
                $output .= '"'.$row['climbFailLevel'].'",';
                $output .= '"'.$row['yellowCard'].'",';
                $output .= '"'.$row['redCard'].'",';
                $output .= '"'.$row['foul'].'",';
                $output .= '"'.$row['defense'].'",';
                $output .= '"'.$row['fallover'].'",';
                $output .= '"'.$row['falloverSave'].'",';
                $output .= '"'.$row['win'].'",';
                $output .= '"'.$row['extraInformation'].'",';
                if($counter == $numResults) {
                  $output .= ''.$row['score'].']';
                }
                else {
                  $output .= ''.$row['score'].'],';
                }
            }
            echo $output;
            mysqli_data_seek($result, 0);
        ?>
      ];
      var autoRockets = [
        <?php
          $output = "";
          $numResults = mysqli_num_rows($result);
          $counter = 0;
          while($row2 = $result2->fetch_assoc()) {
            $counter += 1;
            $output .= '['.$row2['autoHatchRocketsSuccess1'].',';
            $output .= $row2['autoHatchRocketsSuccess2'].',';
            $output .= $row2['autoHatchRocketsSuccess3'].',';
            $output .= $row2['autoCargoRocketsSuccess1'].',';
            $output .= $row2['autoCargoRocketsSuccess2'].',';
            $output .= $row2['autoCargoRocketsSuccess3'].',';
            $output .= $row2['autoHatchRocketsFail1'].',';
            $output .= $row2['autoHatchRocketsFail2'].',';
            $output .= $row2['autoHatchRocketsFail3'].',';
            $output .= $row2['autoCargoRocketsFail1'].',';
            $output .= $row2['autoCargoRocketsFail2'].',';
            if($counter == $numResults) {
              $output .= ''.$row2['autoCargoRocketsFail3'].']';
            }
            else {
              $output .= ''.$row2['autoCargoRocketsFail3'].'],';
            }
          }
          echo $output;
          mysqli_data_seek($result2, 0);
        ?>
      ];
      var autoShip = [
        <?php
          $output = "";
          $numResults = mysqli_num_rows($result);
          $counter = 0;
          while($row3 = $result3->fetch_assoc()) {
            $counter += 1;
            $output .= '['.$row3['autoHatchShipSuccess1'].',';
            $output .= $row3['autoHatchShipSuccess2'].',';
            $output .= $row3['autoHatchShipSuccess3'].',';
            $output .= $row3['autoCargoShipSuccess1'].',';
            $output .= $row3['autoCargoShipSuccess2'].',';
            $output .= $row3['autoCargoShipSuccess3'].',';
            $output .= $row3['autoHatchShipFail1'].',';
            $output .= $row3['autoHatchShipFail2'].',';
            $output .= $row3['autoHatchShipFail3'].',';
            $output .= $row3['autoCargoShipFail1'].',';
            $output .= $row3['autoCargoShipFail2'].',';
            if($counter == $numResults) {
              $output .= ''.$row3['autoCargoShipFail3'].']';
            }
            else {
              $output .= ''.$row3['autoCargoShipFail3'].'],';
            }
          }
          echo $output;
          mysqli_data_seek($result3, 0);
        ?>
      ];
      var teleopRockets = [
        <?php
          $output = "";
          $numResults = mysqli_num_rows($result);
          $counter = 0;
          while($row4 = $result4->fetch_assoc()) {
            $counter += 1;
            $output .= '['.$row4['teleopHatchRocketsSuccess1'].',';
            $output .= $row4['teleopHatchRocketsSuccess2'].',';
            $output .= $row4['teleopHatchRocketsSuccess3'].',';
            $output .= $row4['teleopCargoRocketsSuccess1'].',';
            $output .= $row4['teleopCargoRocketsSuccess2'].',';
            $output .= $row4['teleopCargoRocketsSuccess3'].',';
            $output .= $row4['teleopHatchRocketsFail1'].',';
            $output .= $row4['teleopHatchRocketsFail2'].',';
            $output .= $row4['teleopHatchRocketsFail3'].',';
            $output .= $row4['teleopCargoRocketsFail1'].',';
            $output .= $row4['teleopCargoRocketsFail2'].',';
            if($counter == $numResults) {
              $output .= ''.$row4['teleopCargoRocketsFail3'].']';
            }
            else {
              $output .= ''.$row4['teleopCargoRocketsFail3'].'],';
            }
          }
          echo $output;
          mysqli_data_seek($result4, 0);
        ?>
      ];
      var teleopShip = [
        <?php
          $output = "";
          $numResults = mysqli_num_rows($result);
          $counter = 0;
          while($row5 = $result5->fetch_assoc()) {
            $counter += 1;
            $output .= '['.$row5['teleopHatchShipSuccess1'].',';
            $output .= $row5['teleopHatchShipSuccess2'].',';
            $output .= $row5['teleopHatchShipSuccess3'].',';
            $output .= $row5['teleopCargoShipSuccess1'].',';
            $output .= $row5['teleopCargoShipSuccess2'].',';
            $output .= $row5['teleopCargoShipSuccess3'].',';
            $output .= $row5['teleopHatchShipFail1'].',';
            $output .= $row5['teleopHatchShipFail2'].',';
            $output .= $row5['teleopHatchShipFail3'].',';
            $output .= $row5['teleopCargoShipFail1'].',';
            $output .= $row5['teleopCargoShipFail2'].',';
            if($counter == $numResults) {
              $output .= ''.$row5['teleopCargoShipFail3'].']';
            }
            else {
              $output .= ''.$row5['teleopCargoShipFail3'].'],';
            }
          }
          echo $output;
          mysqli_data_seek($result6, 0);
        ?>
      ];
      var robotInfo = [
        <?php
          $output = "";
          while($row7 = $result7->fetch_assoc()) {
            $output .= $row7['speedMPS'].',';
            $output .= $row7['weightP'].',';
            $output .= '"'.$row7['strength'].'",';
            $output .= $row7['numWheels'].',';
            $output .= '"'.$row7['omniWheels'].'",';
            $output .= '"'.$row7['canPlaceHatch2'].'",';
            $output .= '"'.$row7['canPlaceHatch3'].'",';
            $output .= '"'.$row7['canPlaceCargo2'].'",';
            $output .= '"'.$row7['canPlaceCargo3'].'",';
            $output .= '"'.$row7['canPickUpHatch'].'",';
            $output .= '"'.$row7['speedPickUp'].'",';
            $output .= '"'.$row7['canClimb2'].'",';
            $output .= '"'.$row7['canClimb3'].'",';
            $output .= '"'.$row7['extraInformation'].'"';
          }
          echo $output;
          mysqli_data_seek($result7, 0);
        ?>
      ];
    </script>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h3 style="color:purple; font:bold;">A-Team Scouting Page</h3>
        <ul class="nav nav-pills pull-right">
				  <li><a href="homePage.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">Home Page</a></li>
          <li><a href="teamList.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">Team List</a></li>
					<li><a href="addTeam.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">Add Team</a></li>
					<li><a href="robot.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">Add Robot</a></li>
					<li><a href="scoutTeam.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">Scout Team</a></li>
          <li><a href="importPaper.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">Paper Scout</a></li>
					<li><a href="addMatchCount.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">Add Match</a></li>
					<li><a href="viewMatchSetNumber.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>">View Match</a></li>
          <li class="active"><a href="viewTeam.php?u=<?php echo $user; ?>&s=<?php echo $scouter; ?>&num=<?php echo $teamNumber; ?>">View Team</a></li>
        </ul>
      </div>
      <h2>Team <?php echo $teamNumber; ?>: <?php echo $teamName; ?></h2><br />

      <div id="teamInformation">
        <table id="teamInfo">
          <tr>
            <th>School</th>
            <th>Email</th>
            <th>Age</th>
            <th>Location</th>
          </tr>
          <tr>
            <?php
              echo '<td>'.$teamSchoolName.'</td>';
              echo '<td>'.$teamEmail.'</td>';
              echo '<td>'.$teamAge.' Years</td>';
              echo '<td>'.$teamLocation.'</td>';
            ?>
          </tr>
        </table><br />
        <h2>Robot Info</h2>
        <table id="robotInfo" style="border: 1px solid black;">
          <tr>
            <th>Speed (m/s)</th>
            <th>Weight (lb)</th>
            <th>Strength</th>
            <th>Number of Wheels</th>
            <th>Omni-Wheels</th>
            <th>Hatch 2?</th>
            <th>Hatch 3?</th>
            <th>Cargo 2?</th>
            <th>Cargo 3?</th>
            <th>Pick Up Hatch?</th>
            <th>Speed of Pick-Up</th>
            <th>Climb Level 2?</th>
            <th>Climb Level 3?</th>
            <th>Extra Information</th>
          </tr>
          <?php
            if($result7->num_rows > 0) {
              while($row7 = $result7->fetch_assoc()) {
                echo '<tr>';
                echo '<td>'.$row7['speedMPS'].'</td>';
                echo '<td>'.$row7['weightP'].'</td>';
                echo '<td>'.$row7['strength'].'</td>';
                echo '<td>'.$row7['numWheels'].'</td>';
                echo '<td>'.$row7['omniWheels'].'</td>';
                echo '<td>'.$row7['canPlaceHatch2'].'</td>';
                echo '<td>'.$row7['canPlaceHatch3'].'</td>';
                echo '<td>'.$row7['canPlaceCargo2'].'</td>';
                echo '<td>'.$row7['canPlaceCargo3'].'</td>';
                echo '<td>'.$row7['canPickUpHatch'].'</td>';
                echo '<td>'.$row7['speedPickUp'].'</td>';
                echo '<td>'.$row7['canClimb2'].'</td>';
                echo '<td>'.$row7['canClimb3'].'</td>';
                echo '<td>'.$row7['extraInformation'].'</td>';
                echo '</tr>';
              }
            }
            else {
              echo '<tr><b>No results found.</b></tr>';
            }
          ?>
        </table>
        <h3>Auto Info</h3>
        <table id="autoInfo">
          <tr>
            <th>Can Place Hatch</th>
            <th>Can Place Cargo</th>
            <th>Extra Information</th>
          </tr>
          <tr>
            <?php
              if(isset($canCollectHatch)) {
                echo '<td>'.$canCollectHatch.'</td>';
                echo '<td>'.$canCollectCargo.'</td>';
                echo '<td>'.$autoExtraInformation.'</td>';
              }
              else {
                for($i = 0;$i < 3;$i++) {
                  echo '<td>No Information Available</td>';
                }
              }
            ?>
          </tr>
        </table>
        <h3>Teleop Info</h3>
        <table id="teleopInfo">
          <tr>
            <th>Average Number of Hatches Per Round</th>
            <th>Average Cargo Per Round</th>
            <th>Average Climb Level 2 Speed in Seconds</th>
            <th>Average Climb Level 2 Speed in Seconds</th>
            <th>Extra Information</th>
          </tr>
          <tr>
            <?php
              if(isset($averageNumHatches)) {
                if($result4->num_rows > 0) {
                  $numResults = mysqli_num_rows($result4);
                  $hatchSum = 0;
                  $cargoSum = 0;

                  while($row4 = $result4->fetch_assoc()) {
                    $hatchSum += $row4['teleopHatchRocketsSuccess1'];
                    $hatchSum += $row4['teleopHatchRocketsSuccess2'];
                    $hatchSum += $row4['teleopHatchRocketsSuccess3'];

                    $cargoSum += $row4['teleopCargoRocketsSuccess1'];
                    $cargoSum += $row4['teleopCargoRocketsSuccess2'];
                    $cargoSum += $row4['teleopCargoRocketsSuccess3'];
                  }

                  while($row5 = $result5->fetch_assoc()) {
                    $hatchSum += $row5['teleopHatchShipSuccess1'];
                    $hatchSum += $row5['teleopHatchShipSuccess2'];
                    $hatchSum += $row5['teleopHatchShipSuccess3'];

                    $cargoSum += $row5['teleopCargoShipSuccess1'];
                    $cargoSum += $row5['teleopCargoShipSuccess2'];
                    $cargoSum += $row5['teleopCargoShipSuccess3'];
                  }

                  $hatchAverage = (int) $hatchSum / $numResults;
                  $cargoAverage = (int) $hatchSum / $numResults;

                  echo '<td>'.$hatchAverage;
                  if($hatchAverage > $averageNumHatches) {
                    $difference = $hatchAverage - $averageNumHatches;
                    echo ' ('.$difference.' more than the team said they\'d get)</td>';
                  }
                  else if($hatchAverage < $averageNumHatches) {
                    $difference = $averageNumHatches - $hatchAverage;
                    echo ' ('.$difference.' less than the team said they\'d get)</td>';
                  }
                  else {
                    echo '</td>';
                  }

                  echo '<td>'.$cargoAverage;
                  if($cargoAverage > $averageNumCargo) {
                    $difference = $cargoAverage - $averageNumCargo;
                    echo ' ('.$difference.' More Than the Team Said They\'d Get)</td>';
                  }
                  else if($cargoAverage < $averageNumCargo) {
                    $difference = $averageNumCargo - $cargoAverage;
                    echo ' ('.$difference.' Less Than the Team Said They\'d Get)</td>';
                  }
                  else {
                    echo '</td>';
                  }
                }
                else {
                  echo '<td>Estimated '.$averageNumHatches.' Seconds</td>';
                  echo '<td>Estimated '.$averageNumCargo.' Seconds</td>';
                }

                if($speedClimb2 == 0) {
                  echo '<td>They Can\'t Climb Level 2</td>';
                }
                else {
                  echo '<td>Estimated '.$speedClimb2.' Seconds</td>';
                }

                if($speedClimb3 == 0) {
                  echo '<td>They Can\'t Climb Level 3</td>';
                }
                else {
                  echo '<td>Estimated '.$speedClimb3.' Seconds</td>';
                }

                echo '<td>'.$teleopExtraInformation.'</td>';
              }
              else {
                for($i = 0;$i < 5;$i++) {
                  echo '<td>No Information Available</td>';
                }
              }
            ?>
          </tr>
        </table>
        <h2>Scouts</h2>
        <table id="data" style="border: 1px solid black;">
          <tr>
            <th>Match Number</th>
            <th>Start Position</th>
            <th>Climb Success</th>
            <th>Climb Level</th>
            <th>Climb Fail</th>
            <th>Climb Fail Level</th>
            <th>Yellow Cards</th>
            <th>Red Cards</th>
            <th>Other Fouls</th>
            <th>Defense</th>
            <th>Fallovers</th>
            <th>Fallover Saves</th>
            <th>Alliance Win</th>
            <th>Extra Information</th>
            <th>Match Score</th>
          </tr>
          <script type="text/javascript">
            var table = document.getElementById("data");

            for(var i = 0;i < mainScoutInformation.length;i++) {
              var row = table.insertRow(i + 1);
              for(var j = 0;j < 15;j++) {
                var cell = row.insertCell(j);
                if(mainScoutInformation[i][j] === "L1") {
                  cell.innerHTML = "Level 1";
                }
                else if(mainScoutInformation[i][j] === "L2") {
                  cell.innerHTML = "Level 2";
                }
                else if(mainScoutInformation[i][j] === "L3") {
                  cell.innerHTML = "Level 3";
                }
                else {
                  cell.innerHTML = mainScoutInformation[i][j];
                }
              }
            }
          </script>
        </table>
        <h2>Auto Scouts</h2>
        <table id="autoData" style="border: 1px solid black;">
          <tr>
            <th>Match Number</th>
            <th>Hatch Level 1 Success</th>
            <th>Hatch Level 2 Success</th>
            <th>Hatch Level 3 Success</th>
            <th>Cargo Level 1 Success</th>
            <th>Cargo Level 2 Success</th>
            <th>Cargo Level 3 Success</th>
            <th>Hatch Level 1 Fail</th>
            <th>Hatch Level 2 Fail</th>
            <th>Hatch Level 3 Fail</th>
            <th>Cargo Level 1 Fail</th>
            <th>Cargo Level 2 Fail</th>
            <th>Cargo Level 3 Fail</th>
            <th>Hatch Ship Success</th>
            <th>Cargo Ship Success</th>
            <th>Hatch Ship Fail</th>
            <th>Cargo Ship Fail</th>
          </tr>
          <script type="text/javascript">
            var table = document.getElementById("autoData");

            for(var i = 0;i < mainScoutInformation.length;i++) {
              var row = table.insertRow(i + 1);

              var cell = row.insertCell(0);
              cell.innerHTML = mainScoutInformation[i][0];
              
              for(var j = 1;j < 13;j++) {
                var cell = row.insertCell(j);
                cell.innerHTML = autoRockets[i][j - 1];
                
                if(autoRockets[i][j - 1] != 0) {
                  cell.style.fontSize = "16px";
                  cell.style.fontWeight = 1000;
                }
                
              }

              for(var j = 0;j < 4;j++) {
                var cell = row.insertCell(j + 13);
                cell.innerHTML = autoShip[i][j] + autoShip[i][j + 1] + autoShip[i][j + 2];
                
                if((autoShip[i][j] + autoShip[i][j + 1] + autoShip[i][j + 2]) != 0) {
                  cell.style.fontSize = "16px";
                  cell.style.fontWeight = 1000;
                }
              }
            }
          </script>
        </table>
        <h2>Teleop Scouts</h2>
        <table id="teleopData" style="border: 1px solid black;">
          <tr>
            <th>Match Number</th>
            <th>Hatch Level 1 Success</th>
            <th>Hatch Level 2 Success</th>
            <th>Hatch Level 3 Success</th>
            <th>Cargo Level 1 Success</th>
            <th>Cargo Level 2 Success</th>
            <th>Cargo Level 3 Success</th>
            <th>Hatch Level 1 Fail</th>
            <th>Hatch Level 2 Fail</th>
            <th>Hatch Level 3 Fail</th>
            <th>Cargo Level 1 Fail</th>
            <th>Cargo Level 2 Fail</th>
            <th>Cargo Level 3 Fail</th>
            <th>Hatch Ship Success</th>
            <th>Cargo Ship Success</th>
            <th>Hatch Ship Fail</th>
            <th>Cargo Ship Fail</th>
          </tr>
          <script type="text/javascript">
            var table = document.getElementById("teleopData");

            for(var i = 0;i < mainScoutInformation.length;i++) {
              var row = table.insertRow(i + 1);

              var cell = row.insertCell(0);
              cell.innerHTML = mainScoutInformation[i][0];
              
              for(var j = 1;j < 13;j++) {
                var cell = row.insertCell(j);
                cell.innerHTML = teleopRockets[i][j - 1];

                if(teleopRockets[i][j - 1] != 0) {
                  cell.style.fontSize = "16px";
                  cell.style.fontWeight = 1000;
                }
              }

              for(var j = 0;j < 4;j++) {
                var cell = row.insertCell(j + 13);
                cell.innerHTML = teleopShip[i][j] + teleopShip[i][j + 1] + teleopShip[i][j + 2];

                if((teleopShip[i][j] + teleopShip[i][j + 1] + teleopShip[i][j + 2]) != 0) {
                  cell.style.fontSize = "16px";
                  cell.style.fontWeight = 1000;
                }
              }
            }
          </script>
        </table>
        <br />
        <canvas id="linegraph" width="700" height="425">
        </canvas>
        <script type="text/javascript">
          var canvas = document.getElementById("linegraph");
          var yLine = canvas.getContext("2d");
          yLine.moveTo(50, 50);
          yLine.lineTo(50, 350);
          yLine.lineWidth = 2;
          yLine.stroke();

          var xLine = canvas.getContext("2d");
          xLine.moveTo(50, 350);
          xLine.lineTo(700, 350);
          xLine.lineWidth = 2;
          xLine.stroke();

          var text = canvas.getContext("2d");
          text.font = "50px Times New Roman";
          text.fillText("Points Per Match", 200, 50);
          text.restore();

          /*
          var text = canvas.getContext("2d");
          text.font = "30px Times New Roman";
          text.rotate(Math.PI * 2);
          text.fillText("Points", 0, 200);
          text.restore();
          */

          var text = canvas.getContext("2d");
          text.font = "30px Times New Roman";
          text.fillText("Match Number", 300, 400);
          text.restore();

          for(var i = 0;i < 15;i++) {
            var ym = canvas.getContext("2d");
            ym.moveTo(45, 330 - (i * 20));
            ym.lineTo(55, 330 - (i * 20));
            ym.lineWidth = 1;
            ym.stroke();

            var text = canvas.getContext("2d");
            text.font = "20px Times New Roman";
            text.fillText((i + 1) * 5, 20, 335 - (i * 20));
          }
          
          var xmdimensions = [];
          var ymdimensions = [];
          var xdifference = 650 / mainScoutInformation.length;

          for(var i = 0;i < mainScoutInformation.length;i++) {
            xmdimensions[i] = xdifference * (i + 1) + 20;
            ymdimensions[i] = 350 - (mainScoutInformation[i][14] * 4);


            var xm = canvas.getContext("2d");
            xm.moveTo(xmdimensions[i], 355);
            xm.lineTo(xmdimensions[i], 345);
            xm.stroke();

            var text = canvas.getContext("2d");
            text.font = "20px Times New Roman";
            text.fillText(mainScoutInformation[i][0], xmdimensions[i] - 5, 375);
            
            var dot = canvas.getContext("2d");
            dot.beginPath();
            dot.arc(xmdimensions[i], ymdimensions[i], 4, 0, 2 * Math.PI);
            dot.stroke();
            dot.fillStyle = "#000000";
            dot.fill();
          }

          for(var i = 1;i < mainScoutInformation.length;i++) {
            var connect = canvas.getContext("2d");
            connect.moveTo(xmdimensions[i - 1], ymdimensions[i - 1]);
            connect.lineTo(xmdimensions[i], ymdimensions[i]);
            connect.lineWidth = 1;
            connect.stroke();
          }

          var connect = canvas.getContext("2d");
          connect.moveTo(xmdimensions[0], ymdimensions[0]);
          connect.lineTo(50, 350);
          connect.lineWidth = 1;
          connect.stroke();
        </script><br />
        <h2>All Matches</h2>
        <table id="matches" style="border: 1px solid black;">
          <tr>
            <th>Match Number</th>
            <th>Blue Team 1</th>
            <th>Blue Team 2</th>
            <th>Blue Team 3</th>
            <th>Red Team 1</th>
            <th>Red Team 2</th>
            <th>Red Team 3</th>
          </tr>
          <?php
            //Loop through results
            while($row6 = $result6->fetch_assoc()){
              //Display customer info
              $output ='<tr>';
              $output .= '<td><a href="viewMatch.php?num='.$row6['matchNumber'].'"class="btn btn-default btn-sm" style="font-size: 14px;"><b>'.$row6['matchNumber'].'</b></td>';
              if($row6['blueTeam1'] == $teamNumber) {
                $output .= '<td><a href="viewTeam.php?num='.$row6['blueTeam1'].'"class="btn btn-default btn-sm" style="font-size: 16px; color: rgb(255, 102, 0)"><b>'.$row6['blueTeam1'].'</b></td>';
              }
              else {
                $output .= '<td><a href="viewTeam.php?num='.$row6['blueTeam1'].'"class="btn btn-default btn-sm" style="font-size: 14px; color: rgb(8, 165, 0)"><b>'.$row6['blueTeam1'].'</b></td>';
              }
              if($row6['blueTeam2'] == $teamNumber) {
                $output .= '<td><a href="viewTeam.php?num='.$row6['blueTeam2'].'"class="btn btn-default btn-sm" style="font-size: 16px; color: rgb(255, 102, 0)"><b>'.$row6['blueTeam2'].'</b></td>';
              }
              else {
                $output .= '<td><a href="viewTeam.php?num='.$row6['blueTeam2'].'"class="btn btn-default btn-sm" style="font-size: 14px; color: rgb(8, 165, 0)"><b>'.$row6['blueTeam2'].'</b></td>';
              }
              if($row6['blueTeam3'] == $teamNumber) {
                $output .= '<td><a href="viewTeam.php?num='.$row6['blueTeam3'].'"class="btn btn-default btn-sm" style="font-size: 16px; color: rgb(255, 102, 0)"><b>'.$row6['blueTeam3'].'</b></td>';
              }
              else {
                $output .= '<td><a href="viewTeam.php?num='.$row6['blueTeam3'].'"class="btn btn-default btn-sm" style="font-size: 14px; color: rgb(8, 165, 0)"><b>'.$row6['blueTeam3'].'</b></td>';
              }
              if($row6['redTeam1'] == $teamNumber) {
                $output .= '<td><a href="viewTeam.php?num='.$row6['redTeam1'].'"class="btn btn-default btn-sm" style="font-size: 16px; color: rgb(255, 102, 0)"><b>'.$row6['redTeam1'].'</b></td>';
              }
              else {
                $output .= '<td><a href="viewTeam.php?num='.$row6['redTeam1'].'"class="btn btn-default btn-sm" style="font-size: 14px; color: rgb(8, 165, 0)"><b>'.$row6['redTeam1'].'</b></td>';
              }
              if($row6['redTeam2'] == $teamNumber) {
                $output .= '<td><a href="viewTeam.php?num='.$row6['redTeam2'].'"class="btn btn-default btn-sm" style="font-size: 16px; color: rgb(255, 102, 0)"><b>'.$row6['redTeam2'].'</b></td>';
              }
              else {
                $output .= '<td><a href="viewTeam.php?num='.$row6['redTeam2'].'"class="btn btn-default btn-sm" style="font-size: 14px; color: rgb(8, 165, 0)"><b>'.$row6['redTeam2'].'</b></td>';
              }
              if($row6['redTeam3'] == $teamNumber) {
                $output .= '<td><a href="viewTeam.php?num='.$row6['redTeam3'].'"class="btn btn-default btn-sm" style="font-size: 16px; color: rgb(255, 102, 0)"><b>'.$row6['redTeam3'].'</b></td>';
              }
              else {
                $output .= '<td><a href="viewTeam.php?num='.$row6['redTeam3'].'"class="btn btn-default btn-sm" style="font-size: 14px; color: rgb(8, 165, 0)"><b>'.$row6['redTeam3'].'</b></td>';
              }
              $output .='</tr>';
              
              //Echo output
              echo $output;
            }
          ?>
        </table>
      </div>
      <div class="footer">
			<p style="color:purple;">&copy; A-Team Robotics 2018</p>
      </div>
    </div> <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>