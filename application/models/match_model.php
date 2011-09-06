<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// how many matches could we display per page
define('PAGE_SIZE', 15);

class Match_Model extends CI_Model
{
    /**
     * return match list
     * @param string $nick
     * @param string $type
     * @param int $page
     * @return array 
     */
    public function getHistory($nick, $type, $page)
    {
        $array = $this->xmlRequestMatchIds($nick, $type, $page);
        
        if($array != false)
        {
            // check if matches are cached or not
            list($matchesToParse, $matchesCached) = $this->cacheCompareMatchIds($array['matches'], $nick);
            
            if($matchesCached != false)
            {
                sort($matchesCached);
                $matchesCached = array_reverse($matchesCached);
            }
            
            return array('cached' => $matchesCached, 'news' => $matchesToParse, 'count' => $array['count']);
        }
        
        return false;
    }
    
    private function xmlRequestMatchIds($nick, $type, $page)
    {
        $typestr = 'ranked_history';
        if($type != 'ranked' && $type != 'casual' && $type != 'public')
            $typestr ='ranked_history';
        else
            $typestr = $type.'_history';
        
        // xml
        $xml = false;
        $path = 'http://xml.heroesofnewerth.com/xml_requester.php?f='.$typestr.'&opt=nick&nick[]='.$nick;
        
        $xml = simplexml_load_file($path);

        if($xml == false || !isset($xml->$typestr->match))
            return false;
        
        $matchIds = array();
        foreach($xml->$typestr->match as $m)
            $matchIds[] = intval($m->id);
        
        $count = count($matchIds)/PAGE_SIZE;
        $matchIds = array_reverse($matchIds);
        $matchIds = array_chunk($matchIds, PAGE_SIZE);
        
        if(isset($matchIds[$page]))
            return array('matches' => $matchIds[$page], 'count' => $count);
        return false;
    }
    
    private function parseMatchHistory($mids, $nick)
    {
        if(count($mids) == 0)
            return array();
        else
        {
            $str = '';
            foreach($mids as $m)
            {
                $str .= '&mid[]=';
                $str .= $m;
            }

            // get matches
            $xml = false;
            $path = 'http://xml.heroesofnewerth.com/xml_requester.php?f=match_stats&opt=mid'.$str;

            $xml = simplexml_load_file($path);

            if($xml == false || !isset($xml->stats->match))
                return array();
            
            // parse matches
            $matches = array();
            foreach($xml->stats->match as $m)
            {
                list($summ, $teams, $players) = $this->parseMatch($m);
                
                // find the right player
                $user = $players[0];
                foreach($players as $p)
                {
                    if($p['nickname'] == $nick)
                    {
                        $user = $p;
                        break;
                    }
                }
                
                $matches[] = array(
                    'date' =>     $summ['mdt'],
                    'id' =>       $summ['mid'],
                    'color' =>    $user['color'],
                    'name' =>     $summ['mname'],
                    'hero' =>     $user['hero_id'],
                    'k' =>        $user['k'],
                    'd' =>        $user['d'],
                    'a' =>        $user['a'],
                    'won' =>      $user['wins'],
                    'rating' =>   $user['rating'],
                    'ck' =>       $user['creepkills'],
                    'cd' =>       $user['denies'],
                    'gpm' =>      $user['gpm'],
                    'exppm' =>    $user['xpm'],
                    'length' =>   round(floor($summ['time_played']/60))
                    );
            }
            
            // cache matches
            $this->cacheInsertMatches($matches, $nick);
        }
        
        return $matches;
    }

    private function cacheCompareMatchIds($matches, $nick)
    {
        $path = "./application/cache/players/$nick";
        
        // if the file exists, read its content
        $readIDs = array();
        $matchesCached = array();
        
        if(file_exists($path))
        {
            $file = fopen($path, 'r');
            
            //get already cached matches' id
            while(!feof($file))
            {
                $array = unserialize(fgets($file));
                $readIDs[] = $array['id'];
                $matchesCached[$array['id']] = $array;
            }
            
            $cached = array();
            $toCache = array();
            
            foreach($matches as $m)
            {
                if(in_array($m, $readIDs))
                    $cached[] = $m;
                else
                    $toCache[] = $m;
            }
        
            fclose($file);
            
            $cacheReturned = array();
            foreach($cached as $c)
                $cacheReturned[] = $matchesCached[$c];
            
            if(count($toCache) == 0)
                $toCache = false;
            
            return array($toCache, $cacheReturned);
        }
        
        return array($matches, false);
    }
    
    private function cacheGetMatches($mids, $nick)
    {
        $path = "./application/cache/players/$nick";
        
        $matches = array();
        if(file_exists($path))
        {
            $file = fopen($path, 'r');
            
            //get already cached matches' id
            while(!feof($file))
            {
                $array = unserialize(fgets($file));
                
                if(in_array($array['id'], $mids))
                    $matches[] = $array;
            }
            
            fclose($file);
        }
        
        return $matches;
    }
    
    private function cacheInsertMatches($matches, $nick)
    {
        // convert rounded float into REAL ROUNDED FLOAT
        /*foreach($matches as &$ms)
        {
            foreach($ms as &$m)
            {
                if(is_float($m))
                    $m = sprintf('%.1f', $m);
            }
        }*/
        
        $path = "./application/cache/players/$nick";
        
        // if the file exists, read its content
        $readMatches = array();
        $readIDs = array();
        if(file_exists($path))
        {
            $file = fopen($path, 'r');
            
            //get already cached matches' id
            $i = 0;
            while(!feof($file))
            {
                $readMatches[] = unserialize(fgets($file));
                $readIDs[] = $readMatches[$i]['id'];
                $i++;
            }
            
            fclose($file);
            unlink($path);
        }
        
        if(is_writable("./application/cache/players/"))
        {
            $file = fopen($path, 'a');

            // set matches that need to be written
            $writeMatches = $readMatches;
            foreach($matches as $m)
            {
                if(!in_array($m['id'], $readIDs))
                {
                    $writeMatches[] = $m;
                }
            }

            sort($writeMatches);

            // fix weird bug
            if($writeMatches[0] == false)
                unset($writeMatches[0]);

            // write!
            foreach($writeMatches as $m)
            {
                fwrite($file, serialize($m));
                fwrite($file, "\n");
            }

            fclose($file);
        }
    }
    
    public function getMatchStats($match)
    {
        $path = 'http://xml.heroesofnewerth.com/xml_requester.php?f=match_stats&opt=mid&mid[]='.$match;
        $xml = simplexml_load_file($path);
        
        if($xml == false || !isset($xml->stats->match))
            return false;
        
        list($summ, $teams, $players) = $this->parseMatch($xml->stats->match, true);

        $data['summ'] = $summ;
        $data['teams'] = $teams;
        $data['players'] = $players;
        $data['best'] = $this->bestPlayersValues($players);;
        
        return $data;
    }
    
    public function parseMatch($xml, $getClans = false)
    {
        $summ = array();
        $teams = array();
        $players = array();
        
        $summ['mid'] = intval($xml['mid']);
        
        foreach($xml->summ->stat as $child)
            $summ[(string)$child['name']] = (string)$child[0];
        
        $i = 0;
        foreach($xml->team as $team)
        {
            foreach($team->stat as $child)
                $teams[$i][(string)$child['name']] = (string)$child[0];
            
            $i++;
        }

        $i = 0;
        foreach($xml->match_stats->ms as $p)
        {
            $players[$i]['aid'] = intval($p['aid']);

            foreach($p->stat as $s)
                $players[$i][(string)$s['name']] = (string)$s[0];

            $i++;
        }
        
        // sort players by position
        $positions = array();
        
        foreach($players as $p)
            $positions[] = array($p['position'], $p);
            
        sort($positions);
        
        for($i = 0; $i < count($players); $i++)
            $players[$i] = $positions[$i][1];
        
        $color[0] = 'blue';
        $color[1] = 'teal';
        $color[2] = 'purple';
        $color[3] = 'yellow';
        $color[4] = 'orange';
        $color[5] = 'brown';
        $color[6] = 'dgreen';
        $color[7] = 'lblue';
        $color[8] = 'grey';
        $color[9] = 'pink';
        
        if($getClans == true)
        {
            // get clans
            $clans = array();
            for($i = 0; $i < count($players); $i++)
            {
                if($players[$i]['clan_id'] > 0)
                    $clans[$players[$i]['clan_id']] = $players[$i]['clan_id'];
            }
            
            $url = 'http://xml.heroesofnewerth.com/xml_requester.php?f=clan_info&opt=cid';
            
            foreach($clans as $c)
                $url .= '&cid[]='.$c;
            
            $xml = simplexml_load_file($url);
    
            foreach($xml->clans->clan as $c)
            {
                $cid = intval($c['cid']);
                foreach($c->stat as $s)
                {
                    if($s['name'] == 'tag')
                        $clans[$cid] = (string)$s[0];
                }
            }
            
            $clans[0] = false;
        }
        
        for($i = 0; $i < count($players); $i++)
        {
            $table[$i]['aid'] = $players[$i]['aid'];
            $table[$i]['color'] = $color[$players[$i]['position']];
            $table[$i]['position'] = $players[$i]['position'];

            $table[$i]['hero_id'] = $players[$i]['hero_id'];
            
            if($getClans == true)
                $table[$i]['clan_tag'] = $clans[$players[$i]['clan_id']];
                
            $table[$i]['wins'] = intval($players[$i]['wins']);
            $table[$i]['team'] = $players[$i]['team'];
            $table[$i]['nickname'] = $players[$i]['nickname'];
            $table[$i]['bloodlust'] = $players[$i]['bloodlust'];
            $table[$i]['level'] = $players[$i]['level'];
            $table[$i]['k'] = $players[$i]['herokills'];
            $table[$i]['d'] = $players[$i]['deaths'];
            $table[$i]['a'] = $players[$i]['heroassists'];
            $table[$i]['level'] = $players[$i]['level'];
            $table[$i]['rating'] = round(floatval($players[$i]['amm_team_rating']));
            
            if($players[$i]['deaths'] > 0)
            {
                $table[$i]['kd'] = round($players[$i]['herokills']/$players[$i]['deaths'], 2);
                $table[$i]['ad'] = round($players[$i]['heroassists']/$players[$i]['deaths'], 2);
                $table[$i]['kad'] = round(($players[$i]['herokills']+$players[$i]['heroassists'])/$players[$i]['deaths'], 2);
            }
            else
            {
                $table[$i]['kd'] = '-';
                $table[$i]['ad'] = '-';
                $table[$i]['kad'] = '-';
            }
            
            if($players[$i]['secs'] == 0)
                $players[$i]['secs'] = 1;
            if($players[$i]['time_earning_exp'] == 0)
                $players[$i]['time_earning_exp'] = 1;
            
            $table[$i]['herodmg'] = $players[$i]['herodmg'];
            $table[$i]['heroexp'] = $players[$i]['heroexp'];
            $table[$i]['herokillsgold'] = $players[$i]['herokillsgold'];
            $table[$i]['gpm'] = round($players[$i]['gold']/($players[$i]['secs']/60), 2);
            $table[$i]['xpm'] = round($players[$i]['exp']/($players[$i]['time_earning_exp']/60), 2);
            $table[$i]['teamcreepkills'] = $players[$i]['teamcreepkills'];
            $table[$i]['neutralcreepkills'] = $players[$i]['neutralcreepkills'];
            $table[$i]['creepkills'] = $players[$i]['teamcreepkills']+$players[$i]['neutralcreepkills'];
            $table[$i]['denies'] = $players[$i]['denies'];
            $table[$i]['exp_denied'] = $players[$i]['exp_denied'];
            $table[$i]['bdmg'] = $players[$i]['bdmg'];
            $table[$i]['buybacks'] = $players[$i]['buybacks'];
            $table[$i]['goldlost2death'] = $players[$i]['goldlost2death'];
            $table[$i]['gold'] = $players[$i]['gold'];
            $table[$i]['gold_spent'] = $players[$i]['gold_spent'];
            $table[$i]['exp'] = $players[$i]['exp'];
            $table[$i]['apm'] = round($players[$i]['actions']/($players[$i]['secs']/60), 1);
            $table[$i]['wards'] = $players[$i]['wards'];
            
            if ($players[$i]['annihilation'] > 0)
                $table[$i]['maxcombo'] = '5';
            else if ($players[$i]['quadkill'] > 0)
                $table[$i]['maxcombo'] = '4';
            else if ($players[$i]['triplekill'] > 0)
                $table[$i]['maxcombo'] = '3';
            else if ($players[$i]['doublekill'] > 0)
                $table[$i]['maxcombo'] = '2';
            else
                $table[$i]['maxcombo'] = 'None';
            if ($players[$i]['ks15'] > 0)
                $table[$i]['maxks'] = '15';
            else if ($players[$i]['ks10'] > 0)
                $table[$i]['maxks'] = '10';
            else if ($players[$i]['ks9'] > 0)
                $table[$i]['maxks'] = '9';
            else if ($players[$i]['ks8'] > 0)
                $table[$i]['maxks'] = '8';
            else if ($players[$i]['ks7'] > 0)
                $table[$i]['maxks'] = '7';
            else if ($players[$i]['ks6'] > 0)
                $table[$i]['maxks'] = '6';
            else if ($players[$i]['ks5'] > 0)
                $table[$i]['maxks'] = '5';
            else if ($players[$i]['ks4'] > 0)
                $table[$i]['maxks'] = '4';
            else if ($players[$i]['ks3'] > 0)
                $table[$i]['maxks'] = '3';
            else
                $table[$i]['maxks'] = 'None';
                                
            $table[$i]['smackdown'] = $players[$i]['smackdown'];
            $table[$i]['discos'] = $players[$i]['discos'];
        }

        return array($summ, $teams, $table);
    }
    
    private function bestValue($values, $reverse = false)
    {
        foreach($values as &$v)
        {
            if($v == "-")
                $v = "10000000";
            $v = floatval($v);
        }
        
        if($reverse == false)
            arsort($values);
        else
            asort($values);
            
        return array_shift(array_keys($values));
    }
    
    private function bestPlayersValues($players)
    {
        $i = 0;
        $values = array();
        foreach($players as $p)
        {
            foreach($p as $k => $v)
            {
                $values[$k][$i] = $v;
            }
            $i++;
        }
        
        $best = array();
        $best['level'] = $this->bestValue($values['level']);
        $best['k'] = $this->bestValue($values['k']);
        $best['d'] = $this->bestValue($values['d'], true);
        $best['a'] = $this->bestValue($values['a']);
        $best['kd'] = $this->bestValue($values['kd']);
        $best['ad'] = $this->bestValue($values['ad']);
        $best['kad'] = $this->bestValue($values['kad']);
        $best['herodmg'] = $this->bestValue($values['herodmg']);
        $best['gpm'] = $this->bestValue($values['gpm']);
        $best['xpm'] = $this->bestValue($values['xpm']);
        $best['teamcreepkills'] = $this->bestValue($values['teamcreepkills']);
        $best['neutralcreepkills'] = $this->bestValue($values['neutralcreepkills']);
        $best['creepkills'] = $this->bestValue($values['creepkills']);
        $best['denies'] = $this->bestValue($values['denies']);
        $best['bdmg'] = $this->bestValue($values['bdmg']);
        $best['buybacks'] = $this->bestValue($values['buybacks']);
        $best['apm'] = $this->bestValue($values['apm']);
        $best['wards'] = $this->bestValue($values['wards']);
        $best['smackdown'] = $this->bestValue($values['smackdown']);
        
        return $best;
    }
    
    public function htmlGetItems($mid, $aid)
    {
        $this->load->library('domparser');
        
        $html = new simple_html_dom();
        $html->load_file('http://replays.heroesofnewerth.com/player_pop.php?mid='.$mid.'&aid='.$aid);
        $imgs = $html->find('img.item_icon');
        
        $out = '';
		
		/**
		 * WTF?!?
		 */
        for($i = 0; $i < 6; $i++)
        {
            //$str = explode('/', $imgs[$i]->src);
            $out .= '<img src="'.img_url('items/'.basename($imgs[$i]->src)).'"/>';
        }
        
        /** for($i = 3; $i < 6; $i++)
        {
            $str = explode('/', $imgs[$i]->src);
            $out .= '<img src="'.img_url('items/'.$str[5]).'"/>';
        } */
        
        return $out;
    }
    
    public function htmlGetMatches($mids, $nick)
    {
        $this->load->helper('hon');
        
        $matches = $this->parseMatchHistory($mids, $nick);
        
        if(count($matches) > 0)
        {
            $out = '';

            foreach($matches as $m)
            {
                $rating = $m['rating'];
                if($rating != 'No stats' && $rating > 0)
                    $rating = '+'.$rating;

                $won = $m['won'] ? 'green' : 'red';

                $out .= '<tr class="'.$won.'">
                            <td class="icon hero '.$m['color'].'">'.img(img_url('heroes/'.$m['hero'].'.jpg')).'</td>
                            <td class="id"><a href="'.site_url("/match/view/".$m['id']).'">'.$m['id'].'</a></td>
                            <td class="date">'.$m['date'].'</td>
                            <td class="name">'.parseColors($m['name']).'</td>
                            <td class="kills">'.$m['k'].'</td>
                            <td class="deaths">'.$m['d'].'</td>
                            <td class="assists">'.$m['a'].'</td>
                            <td class="ck">'.$m['ck'].'</td>
                            <td class="cd">'.$m['cd'].'</td>
                            <td class="gpm">'.$m['gpm'].'</td>
                            <td class="exppm">'.$m['exppm'].'</td>
                            <td class="length">'.$m['length'].' min</td>
                            <td class="rating">'.$rating.'</td>
                      </tr>';
            }

            return $out;
        }
        
        return '';
    }
}
