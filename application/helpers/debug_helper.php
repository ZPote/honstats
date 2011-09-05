<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function d($var)
{
    echo '<pre class="debug">';
    
    if(is_array($var))
        print_r($var);
    else
        var_dump($var);
    
    echo '</pre>';
}