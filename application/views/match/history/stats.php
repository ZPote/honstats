<?php
//d($stats);
//------ UPGRADES ------
// shield & nick color
$shield = '';
$color = '<span>';
if(!empty($stats['upgrades']['cc']) && $stats['upgrades']['cc'] != 'white')
{
    $shield = img(array(
        'src' => img_url('symbols/'.$stats['upgrades']['cc'].'.png'),
        'width' => '32'));
    $color  = '<span class="'.$stats['upgrades']['cc'].'">';
}

// symbol
$symbol = '';
if(!empty($stats['upgrades']['cs']))
    $symbol = img(array(
        'src' => img_url('symbols/'.$stats['upgrades']['cs'].'.png'),
        'width' => '32',
        'height' => '32'));

//------ STATS ------
$t= 'ranked';
if($type == 'ranked' || $type == 'casual' || $type == 'public')
    $t = $type;

echo '<div class="stats '.$t.'">';

// nick
echo '<div class="nickbox">';
        //echo '<p>'.$shield.$symbol.'</p>';
        echo '<h1 class="nick">'.$shield.' '.$color.$nick.'</span></h1>';
        echo '<h2 class="rating">'.$color.$stats['rating'].'</span></h2>';
echo '</div>';

if(isset($stats['heroes']))
{
    echo '<div class="heroes"><table>';
    echo '<tr>';
    for($i = 0; $i < count($stats['heroes']); $i++)
    {
        list($useless, $name) = explode('_', $stats['heroes'][$i][0]);
        echo '<td class="icon"><img style="width:64px;height:64px" src="http://www.heroesofnewerth.com/images/heroes/'.GetHeroId($name).'/icon_128.jpg"/></td>';
    }
    echo '</tr>';
    echo '<tr>';
    for($i = 0; $i < count($stats['heroes']); $i++)
    {
        echo '<td class="usage">'.$stats['heroes'][$i][1].'%</td>';
    }
    echo '</tr>';
    echo '</table></div>';
}

echo '<div class="avg_stats">';
    echo '<hr/>';
    
    echo '<div class="center">';
        echo '<table><thead><tr><th></th><th></th></thead></tr><tbody>';
        echo '<tr><td class="label">Games played</td><td>:</td><td>'.$stats['games_played'].'</td></tr>';
        echo '<tr><td class="label">Wins</td><td>:</td><td>'.$stats['wins'].'</td></tr>';
        echo '<tr><td class="label">Losses</td><td>:</td><td>'.($stats['games_played']-$stats['wins']).'</td></tr>';
        echo '<tr><td class="label">Winning %</td><td>:</td><td>'.$stats['winning'].'%</td></tr>';
        echo '<tbody></table>';
    echo '</div>';
    
    echo '<hr/>';

    echo '<div class="left">';
        echo '<table><thead><tr><th></th><th></th></thead></tr><tbody>';
        echo '<tr><td class="label">Kills</td><td>:</td><td>'.$stats['kills'].'</td></tr>';
        echo '<tr><td class="label">Deaths</td><td>:</td><td>'.$stats['deaths'].'</td></tr>';
        echo '<tr><td class="label">Assists</td><td>:</td><td>'.$stats['assists'].'</td></tr>';
        echo '<tr class="barre"><td></td><td></td><td></td></tr>';
        echo '<tr><td class="label">K/D ratio</td><td>:</td><td>'.$stats['kd'].'</td></tr>';
        echo '<tr><td class="label">A/D ratio</td><td>:</td><td>'.$stats['ad'].'</td></tr>';
        echo '<tr><td class="label">K/D/A</td><td>:</td><td>'.$stats['kda'].'</td></tr>';
        echo '<tr class="barre"><td></td><td></td></tr>';
        echo '<tr><td class="label">Game length</td><td>:</td><td>'.$stats['game_length'].' min</td></tr>';
        echo '<tr><td class="label">Creep kills</td><td>:</td><td>'.$stats['ck'].'</td></tr>';
        echo '<tr><td class="label">Creep denies</td><td>:</td><td>'.$stats['cd'].'</td></tr>';
        echo '<tr><td class="label">GPM</td><td>:</td><td>'.$stats['gpm'].'</td></tr>';
        echo '<tr><td class="label">Exp/min</td><td>:</td><td>'.$stats['exppm'].'</td></tr>';
        echo '<tr><td class="label">APM</td><td>:</td><td>'.$stats['apm'].'</td></tr>';
        echo '<tr><td class="label">Wards</td><td>:</td><td>'.$stats['wards'].'</td></tr>';
        echo '<tbody></table>';
    echo '</div>';
    
    echo '<div class="right">';
        echo '<table><thead><tr><th></th><th></th></thead></tr><tbody>';
        echo '<tr><td class="label smackdown">Smack Down</td><td class="smackdown">:</td><td class="smackdown">'.$stats['smackdowns'].'</td></tr>';
        echo '<tr><td class="label">Humilation</td><td>:</td><td>'.$stats['humiliations'].'</td></tr>';
        echo '<tr class="barre"><td></td><td></td><td></td></tr>';
        echo '<tr><td class="label">Double tap</td><td>:</td><td>'.$stats['double_tap'].'</td></tr>';
        echo '<tr><td class="label">Hat trick</td><td>:</td><td>'.$stats['hat_trick'].'</td></tr>';
        echo '<tr><td class="label">Quad kill</td><td>:</td><td>'.$stats['quad_kill'].'</td></tr>';
        echo '<tr><td class="label">Annihilation</td><td>:</td><td>'.$stats['annihilation'].'</td></tr>';
        echo '<tr class="barre"><td></td><td></td><td></td></tr>';
        echo '<tr><td class="label">Serial killer(3)</td><td>:</td><td>'.$stats['ks3'].'</td></tr>';
        echo '<tr><td class="label">Ultimate warrior (4)</td><td>:</td><td>'.$stats['ks4'].'</td></tr>';
        echo '<tr><td class="label">Legendary (5)</td><td>:</td><td>'.$stats['ks5'].'</td></tr>';
        echo '<tr><td class="label">Onslaught (6)</td><td>:</td><td>'.$stats['ks6'].'</td></tr>';
        echo '<tr><td class="label">Savage sick (7)</td><td>:</td><td>'.$stats['ks7'].'</td></tr>';
        echo '<tr><td class="label">Domination (8)</td><td>:</td><td>'.$stats['ks8'].'</td></tr>';
        echo '<tr><td class="label">Champion of Newerth (9)</td><td>:</td><td>'.$stats['ks9'].'</td></tr>';
        echo '<tr><td class="label">Bloodbath (10)</td><td>:</td><td>'.$stats['ks10'].'</td></tr>';
        echo '<tr><td class="label">IMMORTAL (15)</td><td>:</td><td>'.$stats['ks15'].'</td></tr>';
        echo '<tr class="barre"><td></td><td></td><td></td></tr>';
        echo '<tr><td class="label">Bloodlust</td><td>:</td><td>'.$stats['bloodlust'].'</td></tr>';
        echo '<tbody></table>';
    echo '</div>';
    
    echo '<div class="clear"></div>';
    
    echo '<hr/>';
    
    echo '<p class="center tsr"><span class="label">TSR</span>: '.$stats['tsr'].'</p>';
echo '</div>';

echo '</div>'; //stats