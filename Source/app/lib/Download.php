<?php
/**
 * Z-Scheduler
 *
 * Last revison: 12.3.2015
 * @copyright	Copyright (c) 2014 ZoraData sdružení <http://www.zoradata.cz> Jaroslav Šourek
 * 
 * Knihovní třída pro podporu stahování
 */

 
class Download extends \Nette\Object
{

   private function __construct() 
   {
   }

   
   public static function file($response, $mimeType, $file, $name) 
   {
      $httpResponse = $response;
      $httpResponse->setContentType($mimeType);
      $httpResponse->setHeader('Content-Description', 'File Transfer');
      $httpResponse->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
      $httpResponse->setHeader('Expires', '0');
      $httpResponse->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
      $httpResponse->setHeader('Pragma', 'public');
      $httpResponse->setHeader('Content-Length', self::fileSize($file));
      ob_clean();
      flush();
      echo file_get_contents($file);
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

   
   public static function image($response, $mimeType, $file, $name) 
   {
      $httpResponse = $response;
      $httpResponse->setContentType($mimeType);
      $httpResponse->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
      $httpResponse->setHeader('Expires', '0');
      $httpResponse->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
      $httpResponse->setHeader('Pragma', 'public');
      echo file_get_contents($file);
   }


   public static function fileSize($file) 
   {
      if (file_exists($file))
         return filesize($file);
      else 0;
   }

}
