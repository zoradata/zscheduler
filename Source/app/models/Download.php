<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz>
 *
 * Knihovní třída pro podporu stahování
 */

 
class Download extends \Nette\Object
{

   private function __construct() 
   {
   }

   
   public static function string($response, $mimeType, $string, $name) 
   {
      $httpResponse = $response;
      $httpResponse->setContentType($mimeType);
      $httpResponse->setHeader('Content-Description', 'File Transfer');
      $httpResponse->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
      $httpResponse->setHeader('Expires', '0');
      $httpResponse->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
      $httpResponse->setHeader('Pragma', 'public');
      $httpResponse->setHeader('Content-Length', strlen($string));
      ob_clean();
      flush();
      echo $string;
   }
   
}
