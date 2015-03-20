<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 *
 * Třída pro autentizaci uživatele
 */


use Nette\Security;


class Authenticator extends \Nette\Object implements Security\IAuthenticator
{

   /** Chybové kódy */
   const ERR_LOGIN = -1;


   /**
    * Autentizace uživatele
    * @param array $credentials Pole s přihlašovacími údaji
    * @return Nette\Security\Identity
    * @throws Security\AuthenticationException
    */
   public function authenticate(array $credentials)
   {
      list($host, $user, $password) = $credentials;
      $param = array('driver'=>'mysqli', 'host'=>$host, 'username'=>$user, 'password'=>$password, 
                     'database'=>'INFORMATION_SCHEMA', 'codepage'=>'utf8');             
      try
      {
         dibi::connect($param);
         $dbUser = dibi::fetchSingle('SELECT user()');
      }
      catch (\DibiException $e)
      {
         $message = $e->getMessage();
         $message = iconv(mb_detect_encoding($message, mb_detect_order(), TRUE), 'UTF-8', $message); 
         throw new Security\AuthenticationException($message, self::ERR_LOGIN);
      }
      return new Security\Identity($dbUser, NULL, $param);
   }
      
}
