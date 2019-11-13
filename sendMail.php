<?php

    require ("LogCustomModules.php");
    
    class SendMail extends LogCustomModules
    {
        
        protected function emailWithoutAttachment($to, $from, $replyto, $name, $subject, $message)
        {
            // set the headers
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            $headers[] = 'Reply-To: '.$name.' <'.$replyto.'>';
            $headers[] = 'From: '.$name.' <'.$from.'>';
            
            // send the email
            $emailSent = mail($to,$subject,$message,implode("\r\n", $headers), "-odb -f $from");
            
            // check that the email is sent
            if (!$emailSent){
                
                $this->logEvent("Failed to send mail to $to: ".error_get_last()['message']);
                
                return error_get_last()['message'];
                
            }else{
                
                $this->logEvent("Mail sent to $to: ".error_get_last()['message']);
                
                return 1;
            }
        }
        
        protected function emailWithAttachment($to, $from, $replyto, $name, $subject, $message, $attachments)
        {
            $boundary = md5("random"); // define boundary with a md5 hash value

            // set headers
            $headers[] = 'MIME-Version: 1.0';
            //$headers[] = 'Content-type: text/html; charset=iso-8859-1';
            $headers[] = 'Reply-To: '.$name.' <'.$replyto.'>';
            $headers[] = 'From: '.$name.' <'.$from.'>';
            $headers[] = "Content-Type: multipart/mixed; boundary = \"".$boundary."\"";
    
            //attachment 
            $eol = PHP_EOL;
            $nmessage = "--".$boundary."\r\n";
            $nmessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
            $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $nmessage .= $message."\r\n\r\n";
    
            // check if attachments is an array
            if (is_array($attachments))
            {
                // get all the attachments and attach
                for ($i = 0; $i < sizeof($attachments); $i++){
        
                    $file_name = $attachments[$i];
        
                    // get content of the file
                    $content = file_get_contents($file_name);
        
                    // encode the content to base64
                    $encoded_content = chunk_split(base64_encode($content));
        
                    $nmessage .= "--".$boundary."\r\n";
                    $nmessage .= "Content-Type: application/octet-stream; name=\"".basename($file_name)."\"\r\n";
                    $nmessage .= "Content-Transfer-Encoding: base64\r\n";
                    $nmessage .= "Content-Disposition: attachment; filename=\"".basename($file_name)."\"\r\n\r\n";
                    $nmessage .= $encoded_content."\r\n\r\n";
                }
            }
            else
            {
                    $file_name = $attachments;
        
                    // get content of the file
                    $content = file_get_contents($file_name);
        
                    // encode the content to base64
                    $encoded_content = chunk_split(base64_encode($content));
        
                    $nmessage .= "--".$boundary."\r\n";
                    $nmessage .= "Content-Type: application/octet-stream; name=\"".basename($file_name)."\"\r\n";
                    $nmessage .= "Content-Transfer-Encoding: base64\r\n";
                    $nmessage .= "Content-Disposition: attachment; filename=\"".basename($file_name)."\"\r\n\r\n";
                    $nmessage .= $encoded_content."\r\n\r\n";
            }
            
            $nmessage .= "--".$boundary."--";
    
            // send the email
            $emailSent = mail($to,$subject,$nmessage,implode("\r\n", $headers), "-odb -f $from");
    
            if (!$emailSent){
                
                $this->logEvent("Failed to send mail to $to: ".error_get_last()['message']);
                return error_get_last()['message'];
                
            }else{
                
                $this->logEvent("Mail sent to $to: ".error_get_last()['message']);
                
                return 1;
            }
            
        }
                
        protected function logEvent($message)
        {
            $this->logError($message, "mailinglog.log");
        }
        
    }
    
?>
