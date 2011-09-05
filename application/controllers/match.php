<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Match extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('hon');
        $this->load->library('chart');
        $this->load->helper('form');
        
        libxml_use_internal_errors(true);
    }
    
    public function index($nick = '')
    {
        $this->output->cache(72000);
        
        $data['nick'] = $nick;
        $this->layout->setTitle("HoN Stats")
                     ->contentAdd('home', $data)
                     ->display();
    }
    
    public function history($nick = 'LordS_', $type = 'ranked', $page = 0, $dbg = 0)
    {
        dieBots();
        //$this->benchmark->mark('start');
        
        // init data
        $data['nick'] = false;
        $data['page'] = $page;
        $data['type'] = $type;
        
        // load models
        $this->load->model('stats_model');
        $this->load->model('match_model');

        // get stats
        $data['stats'] = $this->stats_model->getQuickStats($nick, $type);
        $data['count'] = 0;
        
        //$this->benchmark->mark('stats');
        
        // player found
        if($data['stats'] != false)
        {
            $data['nick'] = $data['stats']['nick'];

            // get match history
            $matchHistory = $this->match_model->getHistory($data['nick'], $type, $page);
            
            $this->benchmark->mark('history');
            
            if($matchHistory != false)
            {
                $data['matches'] = $matchHistory['cached'];
                //$data['count'] = ceil($matchHistory['count']);
                $data['news_id'] = $matchHistory['news'];
                
            }
            else
            {
                $data['matches'] = false;
                $data['news_id'] = false;
                //$data['count'] = 0;
            }
        }
        else
        {
            redirect("match/index/$nick");
            return;
        }
        
        /*$this->load->library('pagination');
        $config['base_url'] = site_url("/match/history/$nick/$type/");
        $config['total_rows'] = $data['count'];
        $config['per_page'] = 1;
        $config['cur_page'] = $page;
        $this->pagination->initialize($config);
        $data['paginator'] = $this->pagination->create_links();*/
        
        //$this->benchmark->mark('recent');
        
        // display
        $title = $data['nick'];
        if($title == false)
            $title = 'Not found';
        
        $this->layout->setTitle($title)
                     ->headerAdd('match/history/header', $data)
                     ->contentAdd('match/history/content', $data)
                     ->display();
    }

    public function playerChange()
    {
        $postnick = $this->input->post('nick');
        
        if(!empty($postnick) && $postnick != false)
            redirect("match/history/$postnick");
        else
            redirect("");
    }

    public function view($id = -1)
    {
        dieBots();
        $this->output->cache(72000);
        
        // load match model
        $this->load->model('match_model');
        
        // get match data
        $matchdata = $this->match_model->getMatchStats($id);
        
        if($matchdata == false)
            $matchdata['mid'] = -1;
        else
            $matchdata['mid'] = $id;
        
        if($matchdata['mid'] == -1)
            $title = 'Not found';
        else
            $title = 'Match '.$matchdata['mid'];
		
        // display
        $this->layout->setTitle($title)
                     ->headerAdd('match/viewer/header', $matchdata)
                     ->contentAdd('match/viewer/content', $matchdata)
                     ->display();
    }
    
    public function matchChange()
    {
        $postmatch = $this->input->post('match');
        
        if(!empty($postmatch) && $postmatch != false)
            redirect("match/view/$postmatch");
    }
}
