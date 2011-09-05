<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stats_Model extends CI_Model
{
    public function getQuickStats($nick)
    {
        return $this->parseQuickStats($this->requestStats($nick));
    }
    
    private function requestStats($nick)
    {
        $path = 'http://xml.heroesofnewerth.com/xml_requester.php?f=player_stats&opt=nick&nick[]='.$nick;
        return simplexml_load_file($path);
    }
    
    private function parseQuickStats($xml)
    {
        // player not found
        if($xml == false || !isset($xml->stats->player_stats))
            return false;
        
        $stats = array();

        foreach($xml->stats->player_stats->stat as $s)
        {
            switch($s['name'])
            {
                case 'nickname':
                    $stats['nick'] = (string)$s[0];
                    break;
                
                // ranking
                case 'acc_pub_skill':
                    $stats['acc_rating'] = (int)$s[0];
                    break;
                case 'rnk_amm_team_rating':
                    $stats['rnk_rating'] = (int)$s[0];
                    break;
                case 'cs_amm_team_rating':
                    $stats['cs_rating'] = (int)$s[0];
                    break;
            }
            
            $type = array('acc', 'rnk', 'cs');
            
            foreach($type as $t)
            {
                switch($s['name'])
                {
                    case $t.'_herokills':
                        $stats[$t.'_kills'] = (int)$s[0];
                        break;
                    case $t.'_deaths':
                        $stats[$t.'_deaths'] = (int)$s[0];
                        break;
                    case $t.'_heroassists':
                        $stats[$t.'_assists'] = (int)$s[0];
                        break;
                    case $t.'_teamcreepkills':
                        $stats[$t.'_ck'] = (int)$s[0];
                        break;
                    case $t.'_denies':
                        $stats[$t.'_cd'] = (int)$s[0];
                        break;
                    case $t.'_secs':
                        $stats[$t.'_secs'] = (int)$s[0];
                        break;
                    case $t.'_games_played':
                        $stats[$t.'_games_played'] = (int)$s[0];
                        break;
                    case $t.'_gold':
                        $stats[$t.'_gold'] = (int)$s[0];
                        break;
                    case $t.'_exp':
                        $stats[$t.'_exp'] = (int)$s[0];
                        break;
                    case $t.'_wards':
                        $stats[$t.'_wards'] = (int)$s[0];
                        break;
                    
                    case $t.'_wins':
                        $stats[$t.'_wins'] = (int)$s[0];
                        break;
                    case $t.'_losses':
                        $stats[$t.'_losses'] = (int)$s[0];
                        break;
                }
            }
        }
        
        foreach($type as $t)
        {
            $stats[$t.'_kd'] = 0;
            $stats[$t.'_ad'] = 0;
            $stats[$t.'_kad'] = 0;

            if($stats[$t.'_deaths'] > 0)
            {
                $stats[$t.'_kd'] = round($stats[$t.'_kills']/$stats[$t.'_deaths'], 2);
                $stats[$t.'_ad'] = round($stats[$t.'_assists']/$stats[$t.'_deaths'], 2);
                $stats[$t.'_kad'] = round(($stats[$t.'_kills']+$stats[$t.'_assists'])/$stats[$t.'_deaths'], 2);
            }

            $stats[$t.'_game_length'] = 0;
            $stats[$t.'_gpm'] = 0;
            $stats[$t.'_expm'] = 0;

            if($stats[$t.'_secs'] > 0)
            {
                $stats[$t.'_game_length'] = $stats[$t.'_secs']/$stats[$t.'_games_played']/60;

                if($stats[$t.'_games_played'] > 0)
                {
                    $stats[$t.'_ck'] /= $stats[$t.'_games_played'];
                    $stats[$t.'_cd'] /= $stats[$t.'_games_played'];
                    $stats[$t.'_ck'] = round($stats[$t.'_ck'], 1);
                    $stats[$t.'_cd'] = round($stats[$t.'_cd'], 1);
                    
                    $stats[$t.'_wards'] = round($stats[$t.'_wards']/$stats[$t.'_games_played'], 1);
                }
                else
                {
                    $stats[$t.'_ck'] = 0;
                    $stats[$t.'_cd'] = 0;
                }
                
                $stats[$t.'_gpm'] = round($stats[$t.'_gold']/($stats[$t.'_secs']/60), 1);
                $stats[$t.'_expm'] = round($stats[$t.'_exp']/($stats[$t.'_secs']/60), 1);
            }
            
            if(($stats[$t.'_wins']+$stats[$t.'_losses']) > 0)
            {
                $stats[$t.'_winpr'] = $stats[$t.'_wins']/($stats[$t.'_wins']+$stats[$t.'_losses']);
            }
        }
        
        //load tsr functions
        $this->load->helper('hon');
        
        // TSR
        // public
        $stats['acc_tsr'] = TSR(array(
            'kd' => $stats['acc_kd'],
            'ad' => $stats['acc_ad'],
            'winpr' => $stats['acc_winpr'],
            'gpm' => $stats['acc_gpm'],
            'expm' => $stats['acc_expm'],
            'cd' => $stats['acc_cd'],
            'ck' => $stats['acc_ck'],
            'wards' => $stats['acc_wards'],
            'game_length' => $stats['acc_game_length']
        ));
        
        // ranked
        $stats['rnk_tsr'] = rTSR(array(
            'kd' => $stats['rnk_kd'],
            'ad' => $stats['rnk_ad'],
            'winpr' => $stats['rnk_winpr'],
            'gpm' => $stats['rnk_gpm'],
            'expm' => $stats['rnk_expm'],
            'cd' => $stats['rnk_cd'],
            'ck' => $stats['rnk_ck'],
            'wards' => $stats['rnk_wards'],
            'game_length' => $stats['rnk_game_length']
        ));
        
        //casual
        $stats['cs_tsr'] = 0;
        
        return $stats;
    }
}