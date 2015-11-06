<?php
/*
 * interface 
 * @author marrselo
 */

interface Core_Service_Mailing_InterfaceMail {    
    public function sendMessage($transport,array $configMail);
}