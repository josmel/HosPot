<?php

/*
 * Send Mail
 * @author marrselo
 */

class Default_Model_Ios implements Core_Service_Pusher_InterfacePush {

    /**
     * 
     * @param array $dataMessage coleccion de parametros()
     */
    public function sendMessage(array $dataMessage,array $dataConfig) 
    {                        
        $context = stream_context_create();       
        stream_context_set_option($context, 'ssl', 'local_cert', $dataConfig['iphone']['pem']);
        stream_context_set_option($context, 'ssl', 'passphrase', $dataConfig['iphone']['pass']);
        
        $socket = stream_socket_client($dataConfig['iphone']['sandbox'], 
                $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $context);
        if (!$socket) {        
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        } else {
        }
        $message=$dataMessage['message'];
        $devices=$dataMessage['device'];
        $payload['aps'] = array('alert' =>$message,'key'=>$dataMessage['idpromotion']);
        $payloadJSON = json_encode($payload);
        for ($i = 0, $size = count($devices); $i < $size; $i++) {
            $deviceToken = $devices[$i];
            $payloadServer = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payloadJSON)) . $payloadJSON;
            $result = fwrite($socket, $payloadServer, strlen($payloadServer));            
        }
//        if (!$result) {
//        } else {
//        }
        fclose($socket);
        return;
    }


}
