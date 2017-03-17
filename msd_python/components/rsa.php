<?php
namespace app\components;

use Yii;
use yii\base\Object;

class rsa  extends Object
{
    static public function decodeing($crypttext)
    {
        $key_content = file_get_contents(Yii::getAlias('@app').'/rsa.key');
        
        $prikeyid    = openssl_get_privatekey($key_content);
        $crypttext   = base64_decode($crypttext);

        if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, OPENSSL_PKCS1_PADDING))
        {
            return $sourcestr;
        }
        return ;
    }
}