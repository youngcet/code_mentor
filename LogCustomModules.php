<?php

    class LogCustomModules
    {
        
        // Constructor
        public function __construct()
        {
            error_log("The Log has been initiated...", 0);
        }
        
        protected function logError($text, $name)
        {
            $text = $text.PHP_EOL; // add end of line
            
            error_log(date("d/m/Y H:m:s")." " .$text, 3, "/var/local/logs/$name");
        }
    }
?>
