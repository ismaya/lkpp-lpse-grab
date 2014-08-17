<?php

function get_field($html, $field)
{
    switch ($field) {
        case 'title':
            $pattern = "/Nama Lelang<\/td><td class=\"horizLine\">(.*)<\/td><\/tr><tr><td class=\"TitleLeft\">Kategori/";
            break;
        case 'category':
            $pattern = "/Kategori<\/td><td class=\"horizLine\">(.*)<\/td><\/tr><tr><td class=\"TitleLeft\">Agency/";
            break;
        case 'satker':
            $pattern = "/Satker<\/td><td class=\"horizLine\">(.*)<\/td><\/tr><tr><td class=\"TitleLeft\">Pagu/";
            break;
        case 'winner':
            $pattern = "/Nama Pemenang<\/td><td class=\"horizLine\">(.*)<\/td><\/tr><tr><td class=\"TitleLeft\">Alamat/";
            break;
        case 'npwp':
            $pattern = "/NPWP<\/td><td class=\"horizLine\">(.*)<\/td><\/tr><tr><td class=\"TitleLeft\">Harga/";
            break;
        case 'price':
            $pattern = "/Harga (.*)<\/td><td class=\"horizLine\">(.*)<\/td><\/tr><\/table><div>/";
            break;
    }

    preg_match($pattern, $html, $match);
    if ($field == 'price') {
        return isset($match[2]) ? strip_tags($match[2]) : null;
    } else {
        return isset($match[1]) ? strip_tags($match[1]) : null;
    }
}

function getHTML($url, $timeout)
{
   $ch = curl_init($url); // initialize curl with given url
   curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set useragent
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
   curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error

   return @curl_exec($ch);
}

function get_request($variable)
{
    return (isset($_POST[$variable]) ? $_POST[$variable] : (isset($_GET[$variable]) ? $_GET[$variable] : 'null'));
}

function is_ajax()
{
    return (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

function debug($variable, $use_print_r = true, $stop = false)
{
    echo '<pre>';
    if ($use_print_r) {
        print_r($variable);
    } else {
        var_dump($variable);
    }
    echo '</pre>';

    if ($stop) die();
}