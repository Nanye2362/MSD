<?php
/** 
 * 公钥加密 
 * 
 * @param string 明文  
 * @return string 密文（base64编码） 
 */  
function encodeing($sourcestr)  
{
    $key_content = file_get_contents('./_test_public.key');  
    $pubkeyid    = openssl_get_publickey($key_content);  
      
    if (openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid))  
    {
        return base64_encode("".$crypttext);  
    }
}

/** 
 * 私钥解密 
 * 
 * @param string 密文（二进制格式且base64编码）
 * @param string 密文是否来源于JS的RSA加密 
 * @return string 明文 
 */  
function decodeing($crypttext)  
{
    $key_content = file_get_contents('./_test.key');  
    $prikeyid    = openssl_get_privatekey($key_content);  
    $crypttext   = base64_decode($crypttext);
   
    if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, OPENSSL_PKCS1_PADDING))  
    {
        return "".$sourcestr;  
    }
    return ;  
}

echo $key = "VMLRjvJQeIsnE7p1S0TeTUujX8QFVt7blXRX7QtQVWNBe5hf9gj/XFo3OEToa0gsTYSWci2YQmVY7EcacS/u1fyIZTEz/F9OgU9RalpZK0hGsIIGJ6sOUWD3qplGoEwq0IhuZXpFfli3AuDhCz6OHIpU4INdDSB+CXLFKHOwX2k=";
echo "\r\n";
parse_str(decodeing($key));
print_r($mail." name:".$name." isrole ".$isrole);

