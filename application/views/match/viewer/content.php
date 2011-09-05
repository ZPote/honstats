<div class="matchstats">
<?php
// match exists
if($mid != -1)
{
?>
<div class="gameinfos">
<?php
    if(isset($summ['mname']))
    {
        echo '<h3>'.parseColors($summ['mname']).'</h3>';
        echo '<p><b>Server</b>: '.$summ['name'].'</p>';
        echo '<p><b>Version</b>: '.$summ['version'].'</p>';
        if($summ['map'] == "caldavar")
            echo '<p><b>Map</b>: Forest of Caldavar</p>';

        $time = '';
        $hrs = floor($summ['time_played']/3600);
        $mins = floor($summ['time_played']/60)-$hrs*60;
        $secs = $summ['time_played']-$hrs*3600-$mins*60;

        if($hrs != 0)
            $time .= $hrs.'hrs '.$mins.'mins '.$secs.'secs';
        else
            $time .= $mins.'mins '.$secs.'secs';

        echo '<p><b>Date</b>: '.$summ['mdt'].'</p>';
        echo '<p><b>Length</b>: '.$time.'</p>';

        // mod
        $mod = '';
        $opts = '';
        if ($summ['nm'] == 1)
            $mod = 'Normal mode';
        else if ($summ['sd'] == 1)
            $mod = 'Single draft';
        else if ($summ['rd'] == 1)
            $mod = 'Random draft';
        else if ($summ['bd'] == 1)
            $mod = 'Banning draft';
        else if ($summ['bp'] == 1)
            $mod = 'Banning pick';	
        else if ($summ['ap'] == 1)
            $mod =  'All pick';
        else if ($summ['ar'] == 1)
            $mod =  'All random';

        $moreopts = array();
        /*if($summ['cas'] == 1)
                $moreopts[] = 'Casual Mode';
        if($summ['no_stats'] == 1)
                $moreopts[] = 'No Stats';
        if($summ['ab'] == 1)
                $moreopts[] = 'Auto Balanced';
        if($summ['hardcore'] == 1)
                $moreopts[] = 'Hardcore';
        if($summ['dev_heroes'] == 1)
                $moreopts[] = 'Dev Heroes';
        if($summ['no_repick'] == 1)
                $moreopts[] = 'No Hero Repick';
        if($summ['no_agi'] == 1)
                $moreopts[] = 'No Agi Heroes';
        if($summ['drp_itm'] == 1)
                $moreopts[] = 'Drop Item';
        if($summ['no_timer'] == 1)
                $moreopts[] = 'No Respawn Timer';
        if($summ['no_swap'] == 1)
                $moreopts[] = 'No Hero Swap';
        if($summ['no_int'] == 1)
                $moreopts[] = 'No Intel Heroes';
        if($summ['alt_pick'] == 1)
                $moreopts[] = 'Atl Hero Picking';
        if($summ['shuf'] == 1)
                $moreopts[] = 'Shuffle Team';
        if($summ['no_str'] == 1)
                $moreopts[] = 'No Str Heroes';
        if($summ['no_pups'] == 1)
                $moreopts[] = 'No Power-Ups';
        if($summ['dup_h'] == 1)
                $moreopts[] = 'Duplicate Heroes';*/

        echo '<p><b>Type</b>: '.$mod.'<p>';

        if(count($moreopts) > 0)
        {
            echo '<p><b>Options</b>: '.$moreopts[0];

            if(count($moreopts) > 1)
            {
                unset($moreopts[0]);
                foreach($moreopts as $m)
                    echo ', '.$m;
            }
            echo '</p>';
        }

        // download link
        echo '<br/><p><a href="'.$summ['url'].'">Download replay</a>';
    }
    else
    {
        echo '<h3>Unknown</h3>';
        echo '<p><b>Server</b>:</p>';
        echo '<p><b>Version</b></p>';
        echo '<p><b>Map</b>:</p>';
        echo '<p><b>Date</b>:</p>';
        echo '<p><b>Length</b>:</p>';
        echo '<p><b>Type</b>:<p>';
        // download link
        echo '<br/><p><a href="#">Download replay</a>';
    }
    
    //winner
    if(intval($teams[0]['tm_wins']) > 0)
    {
        echo'<h2 class="legion hide">';
        echo'Legion wins !';
        echo'</h2>';
    }
    else
    {
        echo '<h2 class="hb hide">';
        echo'Hellbourne wins !';	
        echo'</h2>';
    }

    echo '<h2 class="winner" onclick="$(this).hide();$(\'.hide\').show();">Show winner</h2>';
?>
</div>
    
    <table id="matches">
        <thead>
            <tr>
            <th title="Hero">Hero</th>
            <th title="Name">Name</th>
            <th title="Hero level">Lvl</th>
            <th title="Kills">K</th>
            <th title="Deaths">D</th>
            <th title="Assists">A</th>
            <th title="Kills/Death ratio">K:D</th>
            <th title="Assists/Death ratio">A:D</th>
            <th title="Kills+Assists/Death ratio">K+A:D</th>
            <th title="Hero damage">HDmg</th>
            <th title="Gold/min">GPM</th>
            <th title="Experience/min">Exp/Min</th>
            <th title="Creep kills">Ck</th>
            <th title="Neutral kills">Nk</th>
            <th title="Total creep kills">TCk</th>
            <th title="Creep denies">Cd</th>
            <th title="Building damage">BDmg</th>
            <th title="Buy back">BB</th>
            <th title="Actions/min">APM</th>
            <th title="Wards">W</th>
            <th title="Smackdowns">S</th>
            <th title="Items">Items</th>
            <th><img style="width:24px;height:24px;" src="<?php echo img_url('disconnect.png');?>"/></th>
            </tr>
        </thead>
        <tbody>

    <?php
    $legionDmg = array();
    $legionLegend = array();
    $hbDmg = array();
    $hbLegend = array();
    
    $bestClass = array();
    
    for($i = 0; $i < count($players); $i++)
    {
        foreach($players[$i] as $k => $v)
        {
            $bestClass[$i][$k] = '';
            
            if(isset($best[$k]) && ($i == $best[$k] || $v == '-'))
                $bestClass[$i][$k] = 'best';
        }
    }
        
    for($i = 0; $i < count($players); $i++)
    {
        $team = 'legion';
        if($players[$i]['team'] == 2)
            $team = 'hb';

        $odd = '';
        if ($i%2 == 0)
            $odd = ' odd';

        echo '<tr class="'.$team.$odd.'" id="'.$players[$i]['aid'].'">';
        echo '<td class="icon '.$players[$i]['color'].'"><img src="'.img_url('/heroes/'.$players[$i]['hero_id'].'.jpg').'"/></td>';

        $bloodlust = '';
        if ($players[$i]['bloodlust'] == 1)
           $bloodlust = 'class="bloodlust"';

        $tag = '';
        if($players[$i]['clan_tag'] != false)
            $tag = '['.$players[$i]['clan_tag'].']';
        

        echo '<td '.$bloodlust.'><span><a class="player" href="'.site_url('match/history/'.$players[$i]['nickname']).'">'.$tag.$players[$i]['nickname'].'</a><span></td>';

        echo '<td class="'.$bestClass[$i]['level'].'"><span>'.$players[$i]['level'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['k'].'"><span>'.$players[$i]['k'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['d'].'"><span>'.$players[$i]['d'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['a'].'"><span>'.$players[$i]['a'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['kd'].'"><span>'.$players[$i]['kd'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['ad'].'"><span>'.$players[$i]['ad'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['kad'].'"><span>'.$players[$i]['kad'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['herodmg'].'"><span>'.$players[$i]['herodmg'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['gpm'].'"><span>'.$players[$i]['gpm'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['xpm'].'"><span>'.$players[$i]['xpm'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['teamcreepkills'].'"><span>'.$players[$i]['teamcreepkills'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['neutralcreepkills'].'"><span>'.$players[$i]['neutralcreepkills'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['creepkills'].'"><span>'.$players[$i]['creepkills'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['denies'].'"><span>'.$players[$i]['denies'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['bdmg'].'"><span>'.$players[$i]['bdmg'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['buybacks'].'"><span>'.$players[$i]['buybacks'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['apm'].'"><span>'.$players[$i]['apm'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['wards'].'"><span>'.$players[$i]['wards'].'</span></td>';
        echo '<td class="'.$bestClass[$i]['smackdown'].'"><span>'.$players[$i]['smackdown'].'</span></td>';
        
        echo '<td class="items"></td>';
        
        if ($players[$i]['discos'] == 1)
            echo '<td class="disc"><img src="'.img_url('disconnect.png').'" style="width:32px; height:32px;" /></td>';
        else
            echo '<td class="disc"></td>';
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
        </tobdy>
    </table>
    
    <div class="advanced">
        <h2 class="center">Advanced</h2>
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
            
            // less than 5 players
            $finalColors = array();
            foreach($players as $p)
            {
                if($p['team'] != 2)
                    $finalColors[] = $legionColors[$p['position']];
            }

            echo $this->chart->pie('Legion damage', $legionDmg, $legionLegend, $finalColors);

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
            
            // less than 5 players
            $finalColors = array();
            foreach($players as $p)
            {
                if($p['team'] == 2)
                    $finalColors[] = $hbColors[$p['position']-5];
            }

            echo $this->chart->pie('Hellbourne damage', $hbDmg, $hbLegend, $finalColors);
            ?>
        </div>
        <div class="awards center">
            <?php
            $bestPoints = array();
            $worstPoints = array();
            
            for($i = 0; $i < count($players); $i++)
            {
                $Points[$i] = 0;
                foreach($bestClass[$i] as $value)
                {
                    if($value == 'best')
                        $Points[$i]++;
                }
            }

            $daBest = 0;
            for($i = 0; $i < count($Points); $i++)
            {
                if($Points[$i] > $Points[$daBest])
                    $daBest = $i;
            }

            $dbc = $players[$daBest]['color'];
            $daBest = $players[$daBest]['nickname'];
            
            $bloodlust = '';
            foreach($players as $p)
            {
                if($p['bloodlust'] == 1)
                    $bloodlust = $p['nickname'];
            }
            ?>

            <table>
            <tr><td class="label">Best player:</td><td class="value"><span class="<?php echo $dbc; ?>"><?php echo $daBest; ?></span></td></tr>
            <tr><td class="label">Drew bloodlust:</td><td class="value"><?php echo $bloodlust; ?></span></td></tr>
            </table>
        </div>
    </div>
<?php
}
else
{
    echo '<div class="notfound center">';
    echo '<h1 class="notfound">Not Found</h1>';
}
?>
</div>

<script type="text/javascript" src="<?php echo js_url('qTip/jquery.qtip.min'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('th[title]').qtip({
           position: {
              my: 'bottom center',
              at: 'top center'
           }
        });
    });
</script>
<script  type="text/javascript">
    function loadItems($e)
    {
        $.get('<?php echo site_url("/jquery/getitems/$mid"); ?>/' + $e.parent().attr('id'), function(data) {
            $e.html(data);
        }).error(function()
        {
            loadItems($e);
        });
    }

    $(document).ready(function(){
        $('#matches tbody tr .items').each(function($index){
                var $this = $(this);
                $this.text('...');
                //dont request all together at the same time
                setTimeout(function() { loadItems($this); }, $index*200);
           } 
        );
     });
</script>