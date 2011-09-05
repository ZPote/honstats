<?php
if(isset($matches) && $matches != false)
{
    echo '<div class="matches">';
    echo paginator($count, $page, "player/view/$nick/$type/");
    echo '<table class="tablesort">
        <thead>
        <tr>
            <th>Hero</th>
            <th>Map</th>
            <th>Type</th>
            <th>ID</th>
            <th>Date</th>
            <th>Name</th>
            <th>K</th>
            <th>D</th>
            <th>A</th>
            <th>CK</th>
            <th>CD</th>
            <th>GPM</th>
            <th>Exp/min</th>
            <th>Length</th>
            <th>W.</th>
            <th>S.</th>
            <th>Rating</th>
            <th><img style="width:24px;height:24px" src="http://www.heroesofnewerth.com/images/player_disconnect_128.png"/></th>
        </tr>
        </thead>
        <tbody>';

    
    foreach($matches as $m)
    {
            $rating = $m['rating'];
            if($rating != 'No stats' && $rating > 0)
                $rating = '+'.$rating;

            $won = $m['won'] ? 'green' : 'red';
            $disco = $m['disco'] ? '<img src="http://www.heroesofnewerth.com/images/player_disconnect_128.png"/>' : '';
            
            $gtype = 'gametype_'.$m['type'].'.gif';
            if($m['type'] == 'ap' || $m['type'] == 'ar')
                $gtype = 'gameoption_'.$m['type'].'.gif';

            echo '<tr class="'.$won.'">
                        <td class="icon hero">'.img('http://www.heroesofnewerth.com/images/heroes/'.$m['hero'].'/icon_128.jpg').'</td>
                        <td class="dark icon map">'.img(img_url('maps/'.$m['map'].'.png')).'</td>
                        <td class="dark icon type">'.img('http://www.heroesofnewerth.com/images/'.$gtype).'</td>
                        <td class="id"><a href="'.site_url("/match/view/".$m['id']).'">'.$m['id'].'</a></td>
                        <td class="date">'.$m['date'].'</td>
                        <td class="dark name">'.parseColors($m['name']).'</td>
                        <td class="kills">'.$m['k'].'</td>
                        <td class="deaths">'.$m['d'].'</td>
                        <td class="assists">'.$m['a'].'</td>
                        <td class="ck">'.$m['ck'].'</td>
                        <td class="cd">'.$m['cd'].'</td>
                        <td class="gpm">'.$m['gpm'].'</td>
                        <td class="exppm">'.$m['exppm'].'</td>
                        <td class="length">'.$m['length'].' min</td>
                        <td class="wards">'.$m['wards'].'</td>
                        <td class="smackdowns">'.$m['smackdowns'].'</td>
                        <td class="rating">'.$rating.'</td>
                        <td class="icon disco">'.$disco.'</td>
                    </tr>';
    }

        echo '</tbody></table>';
        echo paginator($count, $page, "player/view/$nick/$type/");
        echo '</div>';
}
else
    echo '<h3 class="center error">This player hasn\'t played any match in this category.</h3>';