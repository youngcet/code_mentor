<?php

    echo cannotCapture (
        [
          [0, 0, 0, 1, 0, 0, 0, 0],
          [0, 0, 0, 0, 0, 0, 0, 0],
          [0, 1, 1, 0, 0, 1, 0, 0],
          [0, 0, 0, 0, 1, 0, 1, 0],
          [0, 1, 0, 0, 0, 1, 0, 0],
          [0, 0, 0, 0, 0, 0, 0, 0],
          [0, 1, 0, 0, 0, 0, 0, 1],
          [0, 0, 0, 0, 1, 0, 0, 0]
        ]
    )."\n";
    
    echo cannotCapture (
        [
          [1, 0, 1, 0, 1, 0, 1, 0],
          [0, 1, 0, 1, 0, 1, 0, 1],
          [0, 0, 0, 0, 1, 0, 1, 0],
          [0, 0, 1, 0, 0, 1, 0, 1],
          [1, 0, 0, 0, 1, 0, 1, 0],
          [0, 0, 0, 0, 0, 1, 0, 1],
          [1, 0, 0, 0, 1, 0, 1, 0],
          [0, 0, 0, 1, 0, 1, 0, 1]
        ]
    );
    
    function cannotCapture($boards){
        $invalidMove = 0;
        
        for ($i = 0; $i < count ($boards); $i++){
            for ($x = 0; $x < count ($boards[$i]); $x++){
                // if the current value is a knight (knight =1)
                if ($boards[$i][$x])
                {
                    // lets check if we have an L-Shape
                    // i.e, knights in chess can move two squares in any direction vertically followed by one square horizontally, or two squares in any direction horizontally followed by one square vertically
                    // 1 = true, L-Shape present then break out of both loops
                    $invalidMove = 
                    (
                        isset ($boards[$i+1][$x-2]) && $boards[$i+1][$x-2] || isset ($boards[$i+1][$x+2]) && $boards[$i+1][$x+2] ||
                        isset ($boards[$i-1][$x-2]) && $boards[$i-1][$x-2] || isset ($boards[$i-1][$x-2]) && $boards[$i-1][$x-2] ||
                        
                        isset ($boards[$i+2][$x-1]) && $boards[$i+2][$x-1] || isset ($boards[$i+2][$x+1]) && $boards[$i+2][$x+1] 
                        
                    ) ? 1: 2;
                    
                    if ($invalidMove) break;
                }
            }
            
            if ($invalidMove) break;
        }
      
        return ($invalidMove == 2 || $invalidMove == 0) ? 'false' : 'true'; // 0 or 2 means false
    }
?>