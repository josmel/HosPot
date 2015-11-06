<?php
/*
 * interface 
 * @author marrselo
 */

interface Core_Service_Pusher_InterfacePush {    
    public function sendMessage(array $dataMessage,array $configPush);
}