<?php

/* --  ENCRYPTION/DECRYPTION LIBRARY    -- (c)2007 wildwestcode.com  -- rlabaw@gmail.com   */

error_reporting(0);

    /*

      SET THE SECRET WORD TO A UNIQUE STRING

      

      DO NOT CHANGE THE SECRET WORD ONCE DATA HAS BEEN ENCRYPTED

      

      YOU WILL NOT BE ABLE TO DECRYPT DATA WITHOUT THE ORIGINAL SECRET WORD



    */

    $secret_word = 'f&6%fd3$7hogph4$2b,m5256;45!4@';



    /*-------------------------------------------------------------------------*/



    $secret_key = substr(md5($secret_word), 0, 32);











function encrypt_data($data,$key)

{

    $td = mcrypt_module_open('rijndael-256', '', 'ofb', '');



    $iv = str_repeat('0',32);



    /* Intialize encryption */

    mcrypt_generic_init($td, $key, $iv);



    /* Encrypt data */

    $encrypted = mcrypt_generic($td, $data);



    /* Terminate encryption handler */

    mcrypt_generic_deinit($td);

    

    return base64_encode($encrypted);



}



function decrypt_data($data,$key)

{

    $data = base64_decode($data);



    /* Open the cipher */

    $td = mcrypt_module_open('rijndael-256', '', 'ofb', '');

    



    $iv = str_repeat('0',32);



    /* Initialize encryption module for decryption */

    mcrypt_generic_init($td, $key, $iv);



    /* Decrypt encrypted string */

    $decrypted = mdecrypt_generic($td, $data);



    /* Terminate decryption handle and close module */

    mcrypt_generic_deinit($td);

    mcrypt_module_close($td);



    return $decrypted;

}

?>