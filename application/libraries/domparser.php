<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DomParser
{
    private $CI;

    public function __construct()
    {
        $this->CI = get_instance();
        
        require_once 'domparser/simple_html_dom.php';
    }
}