<?php

    require ('Error.php');
    
     // get input
    $age = readline ('Enter Age: ');
    $results = CheckAge ($age);
    // check if an error occured
    if (App\Custom\Error::IsAnError ($results)) 
    {
        // handle error
        // $results->GetErrorCode() to get the error code
        // $results->GetError() to get the error message
    }

    function CheckAge ($age) 
    {
        // if $age is not a number, return App\Custom\Error object, else return the $age
        return (! is_numeric ($age)) ? 
            new App\Custom\Error (-101, 'Age not a number') : $age;
    }

    // Understanding the App\Custom\Error object

    // new App\Custom\Error (-101, 'Age not a number') 
    //  - creates a new 'App\Custom\Error' object and sets the error and error code

    // $results->IsAnError($results) checks if the given parameter is 'App\Custom\Error' object 
    //  - if it is 'App\Custom\Error' object an error has occured

    // $results->GetErrorCode() returns the error code, 
    //  - useful for hiding error descriptions to the user, especially if the error message might contain 
    //      sensetive application/server details or an error stack trace (usually returned from Exceptions)

    // $results->GetError() returns the error message, 
    //  - useful for debugging and writing to app logs
?>