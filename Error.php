<?php

    namespace App\Custom;

    class Error
    {
        private $_error;
        private $_error_code;

        public function __construct ($code, $error)
        {
            $this->_error = $error;
            $this->_error_code = $code;

            $this->SetError ($error);
            $this->SetErrorCode ($code);
        }

        private function SetError ($error){$this->_error = $error;}

        private function SetErrorCode ($code){$this->_error_code = $code;}

        public static function IsAnError ($error)
        {
            return (is_a ($error, 'App\Custom\Error')) ? true : false;
        }

        public function GetErrorCode(){return $this->_error_code;}

        public function GetError(){return $this->_error;}
    }
?>