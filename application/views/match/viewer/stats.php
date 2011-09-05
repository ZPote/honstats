<table id="matches">
    <th>Hero</th>
    <th>Name</th>
    <th>Lvl</th>
    <th>K</th>
    <th>D</th>
    <th>A</th>
    <th>K:D</th>
    <th>A:D</th>
    <th>K+A:D</th>
    <th>HDmg</th>
    <th>HExp</th>
    <th>HKGold</th>
    <th>GPM</th>
    <th>Exp/Min</th>
    <th>Ck</th>
    <th>Nk</th>
    <th>TCk</th>
    <th>Cd</th>
    <th>ExpD</th>
    <th>BDmg</th>
    <th>BB</th>
    <th>GLtD</th>
    <th>TotalG</th>
    <th>SpentG</th>
    <th>APM</th>
    <th>W</th>
    <th>S</th>
    <th>BCombo</th>
    <th>BKS</th>
    <th><img style="width:24px;height:24px;" src="http://www.heroesofnewerth.com/images/player_disconnect_128.png"/></th>

<?php
$legionDmg = array();
$legionLegend = array();
$hbDmg = array();
$hbLegend = array();

for ($i = 0; $i < count($players); $i++)
{
    $team = 'legion';
    if($players[$i]['team'] == 2)
        $team = 'hb';
    
    $odd = '';
    if ($i%2 == 0)
        $odd = ' odd';
    
    echo '<tr class="'.$team.$odd.'">';
    echo '<td class="icon '.$players[$i]['color'].'"><img src="http://www.heroesofnewerth.com/images/heroes/'.$players[$i]['hero_id'].'/icon_128.jpg"/></td>';
    
    $bloodlust = '';
    if ($players[$i]['bloodlust'] == 1)
       $bloodlust = 'class="bloodlust"';
    
    $tag = '';
    if($players[$i]['clan_tag'] != false)
        $tag = '['.$players[$i]['clan_tag'].']';
    
    echo '<td '.$bloodlust.'><span><a class="player" href="'.site_url('match/history/'.$players[$i]['nickname']).'">'.$tag.$players[$i]['nickname'].'</a><span></td>';
    
    echo '<td class="'.$best[$i]['level'].'"><span>'.$players[$i]['level'].'</span></td>';
    echo '<td class="'.$best[$i]['k'].'"><span>'.$players[$i]['k'].'</span></td>';
    echo '<td class="'.$best[$i]['d'].'"><span>'.$players[$i]['d'].'</span></td>';
    echo '<td class="'.$best[$i]['a'].'"><span>'.$players[$i]['a'].'</span></td>';
    echo '<td class="'.$best[$i]['kd'].'"><span>'.$players[$i]['kd'].'</span></td>';
    echo '<td class="'.$best[$i]['ad'].'"><span>'.$players[$i]['ad'].'</span></td>';
    echo '<td class="'.$best[$i]['kad'].'"><span>'.$players[$i]['kad'].'</span></td>';
    echo '<td class="'.$best[$i]['herodmg'].'"><span>'.$players[$i]['herodmg'].'</span></td>';
    echo '<td class="'.$best[$i]['heroexp'].'"><span>'.$players[$i]['heroexp'].'</span></td>';
    echo '<td class="'.$best[$i]['herokillsgold'].'"><span>'.$players[$i]['herokillsgold'].'</span></td>';
    echo '<td class="'.$best[$i]['gpm'].'"><span>'.$players[$i]['gpm'].'</span></td>';
    echo '<td class="'.$best[$i]['xpm'].'"><span>'.$players[$i]['xpm'].'</span></td>';
    echo '<td class="'.$best[$i]['teamcreepkills'].'"><span>'.$players[$i]['teamcreepkills'].'</span></td>';
    echo '<td class="'.$best[$i]['neutralcreepkills'].'"><span>'.$players[$i]['neutralcreepkills'].'</span></td>';
    echo '<td class="'.$best[$i]['creepkills'].'"><span>'.$players[$i]['creepkills'].'</span></td>';
    echo '<td class="'.$best[$i]['denies'].'"><span>'.$players[$i]['denies'].'</span></td>';
    echo '<td class="'.$best[$i]['exp_denied'].'"><span>'.$players[$i]['exp_denied'].'</span></td>';
    echo '<td class="'.$best[$i]['bdmg'].'"><span>'.$players[$i]['bdmg'].'</span></td>';
    echo '<td class="'.$best[$i]['buybacks'].'"><span>'.$players[$i]['buybacks'].'</span></td>';
    echo '<td class="'.$best[$i]['goldlost2death'].'"><span>'.$players[$i]['goldlost2death'].'</span></td>';
    echo '<td class="'.$best[$i]['gold'].'"><span>'.$players[$i]['gold'].'</span></td>';
    echo '<td class="'.$best[$i]['gold_spent'].'"><span>'.$players[$i]['gold_spent'].'</span></td>';
    echo '<td class="'.$best[$i]['apm'].'"><span>'.$players[$i]['apm'].'</span></td>';
    echo '<td class="'.$best[$i]['wards'].'"><span>'.$players[$i]['wards'].'</span></td>';
    echo '<td class="'.$best[$i]['smackdown'].'"><span>'.$players[$i]['smackdown'].'</span></td>';
    echo '<td>'.$players[$i]['maxcombo'].'</td>';
    echo '<td>'.$players[$i]['maxks'].'</td>';
    if ($players[$i]['discos'] == 1)
        echo '<td><img style="width:24px;height:24px;" src="http://www.heroesofnewerth.com/images/player_disconnect_128.png"/></td>';
    else
        echo '<td></td>';
    echo '</tr>';
    
    
    // damage pie
    if($players[$i]['team'] == 2)
    {
        $hbDmg[] = $players[$i]['herodmg'];
        $hbLegend[] = $players[$i]['nickname'];
    }
    else
    {
        $legionDmg[] = $players[$i]['herodmg'];
        $legionLegend[] = $players[$i]['nickname'];
    }
}
?>
</table>

<h2 class="center">Advanced</h2>
<div class="advanced">
    <div class="dmg center">
        <?php
        // legion pie
        $legionTotalDmg = array_sum($legionDmg);

        foreach($legionDmg as &$v)
        {
            $v = round($v/$legionTotalDmg*100, 1);
            $v = array($v.'%', $v);
        }

        $legionColors = array(
            '#003CE9',
            '#17BC9A',
            '#4D0076',
            '#FFFC01',
            '#FE8A0E'
        );

        echo $this->chart->pie('Legion damage', $legionDmg, $legionLegend, $legionColors);

        // hellbourne pie
        $hbTotalDmg = array_sum($hbDmg);

        foreach($hbDmg as &$v)
        {
            $v = round($v/$hbTotalDmg*100, 1);
            $v = array($v.'%', $v);
        }

        $hbColors = array(
            '#E55BB0',
            '#959697',
            '#7EBFF1',
            '#106246',
            '#4C2904'
        );

        echo $this->chart->pie('Hellbourne damage', $hbDmg, $hbLegend, $hbColors);
        ?>
    </div>
    <div class="awards center">
        <?php
        //d($best);
        $bestPoints = array();
        $worstPoints = array();
        
        for($i = 0; $i < count($players); $i++)
        {
            $Points[$i] = 0;
            foreach($best[$i] as $b)
            {
                if($b == 'best')
                    $Points[$i] ++;
                if($b == 'worst')
                    $Points[$i] --;
            }
        }
        
        //d($Points);
        
        $daBest = 0;
        $daWorst = 0;
        
        for($i = 0; $i < count($Points); $i++)
        {
            if($Points[$i] > $Points[$daBest])
                $daBest = $i;
            
            if($Points[$i] < $Points[$daWorst])
                $daWorst = $i;
        }
        
        $dbc = $players[$daBest]['color'];
        $dwc = $players[$daWorst]['color'];
        
        $daBest = $players[$daBest]['nickname'];
        $daWorst = $players[$daWorst]['nickname'];
        ?>
        
        <table>
        <tr><td class="label">Best player:</td><td class="value"><span class="<?php echo $dbc; ?>"><?php echo $daBest; ?></span></td></tr>
        <tr><td class="label">Worst player:</td><td class="value"><span class="<?php echo $dwc; ?>"><?php echo $daWorst; ?></span></td></tr>
        </table>
    </div>
</div>