<?php

/*
 * Push android
 * @author rollys
 */

class Default_Model_Android implements Core_Service_Pusher_InterfacePush {

    /**
     * 
     * @param array $dataMessage coleccion de parametros, ejem: token , msg
     * @param array $dataConfig colecciones de configuracion para el push android
     */
    public function sendMessage(array $dataMessage, array $dataConfig) 
    {                        
        $fields = array(
            'registration_ids' => $dataMessage['device'],
            'data' => array("message" => $dataMessage['message']),
        );
        $headers = array(
            'Authorization: key=' . $dataConfig['android']['api'],
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dataConfig['android']['url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            return false;
        }
        curl_close($ch);
        return true;
    }


}
