<?php
namespace App\Tool;

trait HandleParametersTrait{

    private $encryptionMethod = 'AES-256-CBC';
    private $secretkey = 'y{10Oxg1VR|T1"{}apV-}^X=JRUzw[qXOKsOw+DemoC97L1T=l5]gU9e6YJUA*K^LDyy;Esk=Cj*elD6C{Rvxy3;:@jkvTzPAmh:diQy9|]sWXkC{Dn%L^VL2ROnkCkE*qL0\~C[m7u12ROnkCkE*qL0\~C[m7u1';
    private $iv= 'ǡw��z�{ds';
    private function  getIv(){
        
        return $this->iv;
    }
    private function  getEncryptionMethod(){
        return $this->encryptionMethod;
    }
    private function  getSecretKey(){
        return $this->secretkey;
    }
    public function encriptar($textToEncrypt)
    {
        $encryptedMessage = openssl_encrypt($textToEncrypt, $this->getEncryptionMethod(), $this->getSecretKey(),0,$this->getIv());
        return $encryptedMessage;
    }
    public function desencriptar($textToEncrypt)
    {
        $encryptedMessage = openssl_decrypt($textToEncrypt, $this->getEncryptionMethod(), $this->getSecretKey(),0,$this->getIv());
        return $encryptedMessage;
    }

}