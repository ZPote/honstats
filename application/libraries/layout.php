<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    private $CI;
    private $name    = 'default';
    private $title   = 'Title';
    private $header  = false;
    private $content = '';
    private $footer  = false;
    private $vars    = array();


    public function __construct()
    {
        $this->CI = get_instance();
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function headerAdd($name, $data = array())
    {
        if($this->header == false) $this->header = '';
        $this->header .= $this->CI->load->view($name, $data, true);
	return $this;
    }
    
    public function contentAdd($name, $data = array())
    {
        $this->content .= $this->CI->load->view($name, $data, true);
	return $this;
    }
    
    public function footerAdd($name, $data = array())
    {
        if($this->footer == false) $this->footer = '';
        $this->footer .= $this->CI->load->view($name, $data, true);
	return $this;
    }
    
    public function customAdd($var, $name, $data = array())
    {
        $this->vars[$var] .= $this->CI->load->view($name, $data, true);
	return $this;
    }
    
    public function setLayout($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function display()
    {
        // default header and footer
        if($this->header == false)
           $this->header = $this->CI->load->view('default/header', array(), true);
        if($this->footer == false)
           $this->footer = $this->CI->load->view('default/footer', array(), true);
        
	$this->CI->load->view('../layouts/'.$this->name,
                array(
                    'title'   => $this->title,
                    'header'  => $this->header,
                    'content' => $this->content,
                    'footer'  => $this->footer,
                    'custom'  => $this->vars
                    )
                );
    }
}