<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class jQuery extends CI_Controller
{
    public function GetMatches($nick, $mids)
    {
        if(strlen($mids) > 0)
        {
            $MatchIDs = explode('-', $mids);
            $this->load->model('match_model');
            echo $this->match_model->htmlGetMatches($MatchIDs, $nick);
        }
    }
    
    public function GetItems($mid, $aid)
    {
        $this->load->model('match_model');
        echo $this->match_model->htmlGetItems($mid, $aid);
    }
}

