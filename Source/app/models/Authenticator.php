<?php
/**
 * Z-Event
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
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
         throw new Security\AuthenticationException($message, self::ERR_LOGIN);
      }
      return new Security\Identity($dbUser, NULL, $param);
   }
      
}
