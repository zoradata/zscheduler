<?php
// ZoraData - ZDCrypt 
//
// Poslední změna 10.02.2010
//


class ZDCrypt 
{
    private $key;
    private $iv;
    private $type;
    private $mode;


/*    public function __construct($key, $type = MCRYPT_CAST_128, $mode = MCRYPT_MODE_CBC)
    {
       $this->key = $key;
       $this->type = $type;
       $this->mode = $mode;
       $this->iv = str_pad('', mcrypt_get_iv_size($this->type, $this->mode), '1');
    }


    public function encrypt($text)
    {
       $data = mcrypt_encrypt($this->type, $this->key, $text, $this->mode, $this->iv);
       return urlencode(base64_encode($data));
    }


    public function decrypt($text)
    {
       $data = base64_decode(urldecode($text));
       $data = mcrypt_decrypt($this->type, $this->key, $data, $this->mode, $this->iv);
       if (($last = strpos($data, chr(0))) === FALSE)
          return $data;
       else return substr($data, 0, $last);
    }


    public function getIv()
    {
       return $this->iv;
    }*/
}

