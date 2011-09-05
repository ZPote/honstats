<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }
    
    public function index()
    {
        
    }
    
    public function getHeroIcons()
    {
        $this->admin_model->getHeroIcons();
    }
    
    public function getItemIcons()
    {
        $this->admin_model->getItemIcons();
    }
	
	public function emptyCache()
	{
		$this->admin_model->emptyCache();
	}
}
