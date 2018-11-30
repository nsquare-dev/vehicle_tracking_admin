<?php

function isValidJSON($str) {
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
}

function getJson() {
    $json_params = file_get_contents("php://input");

    if (strlen($json_params) > 0 && isValidJSON($json_params)) {
        return json_decode($json_params, true);
    } else {
        return array();
    }
}

function encode($value) {
    if (!$value) {
        return false;
    }
    $text = $value;
    $skey = "jZDE0u9afyOkx50AzgLTQqhnmCyNnLS8";
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
    $en = trim(safe_b64encode($crypttext));
    return $en;
}

function decode($value) {
    if (!$value) {
        return false;
    }
    $crypttext = safe_b64decode($value);
    $skey = "jZDE0u9afyOkx50AzgLTQqhnmCyNnLS8";
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
    $de = trim($decrypttext);
    return $de;
}

function safe_b64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    return $data;
}

function safe_b64decode($string) {
    $data = str_replace(array('-', '_'), array('+', '/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

function replacer($value) {
    return $value === NULL ? "" : $value;
}

function recursive_replacer(& $item, $key) {
    if ($item === null) {
        $item = '';
    }
}

?>