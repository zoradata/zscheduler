<?php
/**
 * ZoraData sdružení - Třídy pro práci s databází MySql
 *
 * Last revison: 25.11.2010
 *
 * @copyright	Copyright (c) 2010 ZoraData sdružení
 * @link	http://www.zoradata.cz
 * @package	zdmysqli
 */



class ZDMail
{
   private $mail;


   function __construct($param = array(), $fromEmail, $fromEmailName)
   {
      $this->mail = new PHPMailer(TRUE); 
      $this->mail->IsSMTP(); 
      $this->mail->Host = $param['host']; 
      $this->mail->SMTPAuth = $param['isSMTPAuth'];; 
      $this->mail->Port = $param['port'];; 
      $this->mail->Username = $param['username']; 
      $this->mail->Password = $param['password']; 
      $this->mail->CharSet = $param['charSet'];
      $this->mail->ContentType = $param['contentType'];
      $this->mail->SetFrom($fromEmail, $fromEmailName); 
      $this->mail->AddReplyTo($fromEmail, $fromEmailName); 
   }


   public function AddAddress($address, $name)
   {
      $this->mail->AddAddress($address, $name); 
   }

   
   public function Subject($text)
   {
      $this->mail->Subject = $text; 
   }

   
   public function Body($text)
   {
      $this->mail->Body = $text; 
   }


   public function Send()
   {
      try
      { 
         $this->mail->Send();
         return NULL;
      }
      catch (phpmailerException $e)
      { 
         return $e->errorMessage(); 
      }
   }
}

