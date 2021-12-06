<?php

    echo scaleTip([0, 0, "I", 1, 1])."\n";
    echo scaleTip([1, 2, 3, "I", 4, 0, 0])."\n";
    echo scaleTip([5, 5, 5, 0, "I", 10, 2, 2, 1]);
    
    function scaleTip($arr){
        $results = '';
        
        foreach (array_keys ($arr, 'I', true) as $key) {
            unset ($arr[$key]);
        }
        
        if (array_sum (array_chunk ($arr, count ($arr) / 2)[0]) > array_sum (array_chunk ($arr, count ($arr) / 2)[1])){
            $results = 'left';
        }else if (array_sum (array_chunk ($arr, count ($arr) / 2)[0]) < array_sum (array_chunk ($arr, count ($arr) / 2)[1])){
            $results = 'right';
        }else{
            $results = 'balanced';
        }
        
        return $results;
    }
?>

