<?php
/*
 * Send Mail
 * @author marrselo
 */
class Default_Mail_Invitation implements Core_Service_Mailing_InterfaceMail
{

    /**
     * 
     * @param type $transport objeto Zend_Mail_Transport_Smtp
     * @param array $confMail coleccion de parametros()
     */
    public function sendMessage($transport, array $confMail)
    {                
        $emailFrom=isset($confMail['emailFrom'])?$confMail['emailFrom']:'';
        $nameFrom=isset($confMail['nameFrom'])?$confMail['nameFrom']:'';
        $nameReceptor=isset($confMail['receptor_name'])?$confMail['receptor_name']:'';
        $emailReceptor=isset($confMail['receptor_email'])?$confMail['receptor_email']:'';
        $body=$this->bodyHtml($confMail);

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyHtml($body);
        $mail->setFrom($emailFrom,$nameFrom);
        $mail->addTo($emailReceptor,$nameReceptor);
        $mail->setSubject('Cambiar contraseña!');
        $mail->send($transport);
    }
    
    public function bodyHtml($confMail)
    {
        $html='
                <html>
                <body>
                Hi!
                <br>
                Para recuperar su contraseña haga click <a href="http://local.movilescolar.com/login/token/'.$confMail['token'].'">aqui</a>.
               <br>
               <b>Movilidad Escolar</b>
                </body>
                </html>';
        return $html;
    }
}