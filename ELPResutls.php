<?php

    $teams = array(
            array("Arsenal",38,14,14,10,8),
            array("Aston Villa",38,9,8,21,-26),
            array("Bournemouth",38,9,7,22,-26),
            array("Brighton",38,9,14,15,-15),
            array("Burnley",38,15,9,14,-7),
            array("Chelsea",38,20,6,12,15),
            array("Crystal Palace",38,11,10,17,-19),
            array("Everton",38,13,10,15,-12),
            array("Leicester City",38,18,8,12,26),
            array("Liverpool",38,32,3,3,52),
            array("Manchester City",38,26,3,9,67),
            array("Manchester Utd",38,18,12,8,30),
            array("Newcastle",38,11,11,16,-20),
            array("Norwich",38,5,6,27,-49),
            array("Sheffield Utd",38,14,12,12,0),
            array("Southampton",38,15,7,16,-9),
            array("Tottenham",38,16,11,11,14),
            array("Watford",38,8,10,20,-28),
            array("West Ham",38,10,9,19,-13),
            array("Wolves",38,15,14,9,11)
        );

    echo EPLResult ($teams, "Liverpool")."\n";
    echo EPLResult ($teams, "Manchester Utd")."\n";
    echo EPLResult ($teams, "Norwich");

    function EPLResult($table, $team){
        
        $teams = array();
        for ($i = 0; $i < sizeof($table); $i++){
            // set the team name as a key and score as the value
            $teams[str_replace(' ', '', $table[$i][0])] = $table[$i][2] * 3 + $table[$i][3];
        }

        // sort by values (scores) in descending order
        // remove spaces in team name (this will be used as a key to get the score)
        // get the index of the team + 1 to get the position of the team in the sorted array
        // set the ordinal suffix from the index returned
        // from the original array search for the team provided to get the index of the array and get the goal difference 
        // (this is because we know the goal diff index)
        arsort($teams); 
        $formattedTeamName = str_replace(' ', '', $team); 
        $teamPosition = array_search($formattedTeamName, array_keys($teams))+1; 
        $place = $teamPosition.substr(date('jS', mktime(0,0,0,1,($teamPosition%10==0?9:($teamPosition%100>20?$teamPosition%10:$teamPosition%100)),2000)),-2);
        $goalDiff = $table[array_search($team, array_column($table, 0))][5];
        
        return sprintf("%s came %s with %u points and a goal difference of %s!", $team, $place, $teams[$formattedTeamName], $goalDiff);
    }
?>

