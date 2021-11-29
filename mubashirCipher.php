<?php

    echo mubashirCipher('mubashir is not amazing')."\n";
    echo mubashirCipher('%$ &%')."\n";
    echo mubashirCipher('evgeny sh is amazing');
    
    function mubashirCipher($message){
        $key = 
            [
                ['m', 'c'], ['u', 'e'], ['b', 'g'], ['a', 'k'],
                ['s', 'v'], ['h', 'x'], ['i', 'z'], ['r', 'y'],
                ['p', 'w'], ['l', 'n'], ['o', 'j'], ['t', 'f'], ['q', 'd']
            ];
        
        $encodedMsg = '';
        $messageCharArray = str_split ($message);
        for ($i = 0; $i < count ($messageCharArray); $i++)
        {
            $keyFound = false;
            for ($k = 0; $k < count ($key); $k++)
            {
                if ($key[$k][1] == $messageCharArray[$i]){
                    $encodedMsg .= $key[$k][0];
                    $keyFound = true;
                    break;
                }else if ($key[$k][0] == $messageCharArray[$i]){
                    $encodedMsg .= $key[$k][1];
                    $keyFound = true;
                    break;
                }
            }
            
            if (! $keyFound) $encodedMsg .= $messageCharArray[$i];
        }
        
        return $encodedMsg;
    }
?>