<?php

namespace AWS\Services\Mail;

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;

/*
 * Copyright (C) 2020 Marco Cantu Gea
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
class SESService {

    private $sender="no-responder@gme.mx";
    private $recipients=array();
    private $subject=null;
    private $plainText=null;
    private $htmlBody=null;
    private $result=null;
    private $sesKey="";
    private $sesSecret="";

    /**
     * Constructor de Clase
     *
     * @param array $recipients
     * @param string $subject
     * @param string $plainText
     * @param string $html_body
     */
    public function __construct(array $recipients=null,string $subject=null,string $plainText=null, string $html_body=null,string $sesKey="",string $sesSecret="")
    {
        $this->recipients = (!is_null($recipients)) ? $recipients : null;
        $this->subject = (!is_null($subject)) ? $subject : null;
        $this->plainText = (!is_null($plainText)) ? $plainText : null;
        $this->htmlBody = (!is_null($html_body)) ? $html_body : null;
        $this->sesKey= (getenv('AWS_SES_KEY')===false) ? $sesKey : getenv('AWS_SES_KEY');
        $this->sesSecret=(getenv('AWS_SES_SECRET')===false)? $sesSecret : getenv('AWS_SES_SECRET') ;

    }

    /**
     * Accion de envio de correo
     *
     * @return self
     */
    public function send(){
        if(count($this->recipients)<=0){
            return null;
        }

        if(is_null($this->subject)){
            return null;

        }
        if(is_null($this->plainText)){
            return null;
        }

        if(is_null($this->htmlBody)){
            $this->htmlBody="";
        }

     
        $this->result= $this->enviarEmailAWS($this->sender,$this->recipients,$this->subject,$this->plainText,$this->htmlBody);
        return $this;
    }

    /**
     * Construye y envia el email
     *
     * @param string $senderEmail
     * @param array $recipients
     * @param string $subject
     * @param string $plaintext_body
     * @param string $html_body
     * @param string $charset
     * @param string $configurationSet
     * @return void
     */
    protected function enviarEmailAWS(string $senderEmail, array $recipients, string $subject, string $plaintext_body, string $html_body, string $charset="UTF-8", string $configurationSet="ConfigSet"){
        
        $credentials = new Credentials($this->sesKey,$this->sesSecret);

        $SesClient = new SesClient([
            'version'       => 'latest',
            'region'        => 'us-west-2',
            'credentials'   => $credentials
        ]);
        $result = [];
        try {
            $result = $SesClient->sendEmail([
                'Destination' => [ 'ToAddresses' => $recipients ],
                'ReplyToAddress' => [ $senderEmail ],
                'Source' => $senderEmail,
                'Message' => [
                    'Body' => [
                        'Html' => [ 'Charset' => $charset, 'Data' => $html_body ],
                        'Text' => [ 'Charset' => $charset, 'Data' => $plaintext_body],
                    ],
                    'Subject' => [ 'Charset' => $charset, 'Data' => $subject ],
                ],
                //'ConfigurationSetName' => $configurationSet,
            ]);
            $result = ["res"=>"success","response"=>$result];
        }catch (AwsException $e){
            $result = ["res"=>"fail","error"=>$e->getMessage()];
        }
        return $result;
    }

    /**
     * Asigna el sender
     * @param string $sender
     * @return  self
     */ 
    public function setSender(string $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Asigna los recipientes
     *
     * @param array $recipients
     * @return self
     */ 
    public function setRecipients(array $recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * asigna el Subject del correo
     *
     * @param string $subject
     * @return self
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Asigna el valor del mensaje
     * @param string $plainText
     * @return  self
     */ 
    public function setPlainText(string $plainText)
    {
        $this->plainText = $plainText;

        return $this;
    }

    /**
     * Asigna el contenido html del cuerpo
     * @param string $htmlBody
     * @return  self
     */ 
    public function setHtmlBody(string $htmlBody)
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * Obtiene la respuesta del envio
     *
     * @return mixed
     */
    public function getResponse(){
        return $this->result;
    }



    /**
     * Get the value of recipients
     */ 
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Get the value of subject
     */ 
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get the value of plainText
     */ 
    public function getPlainText()
    {
        return $this->plainText;
    }

    /**
     * Get the value of htmlBody
     */ 
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * Get the value of sesKey
     */ 
    public function getSesKey()
    {
        return $this->sesKey;
    }

    /**
     * Set the value of sesKey
     *
     * @return  self
     */ 
    public function setSesKey($sesKey)
    {
        $this->sesKey = $sesKey;

        return $this;
    }

    /**
     * Get the value of sesSecret
     */ 
    public function getSesSecret()
    {
        return $this->sesSecret;
    }

    /**
     * Set the value of sesSecret
     *
     * @return  self
     */ 
    public function setSesSecret($sesSecret)
    {
        $this->sesSecret = $sesSecret;

        return $this;
    }
}