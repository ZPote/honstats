<div class="match_history">
<?php
// player found
if($nick != false)
{
    //$paginator = '';
    // stats type
    $t = 'rnk';
    if($type == 'public')
        $t = 'acc';
    else if($type == 'casual')
        $t = 'cs';
    
    $currentRating = $stats[$t.'_rating'];
        
    echo '<div class="stats">
        <h1><a href="'.site_url('/match/history/'.$stats['nick']).'">'.$stats['nick'].'</a></h1>
        <h2>'.$currentRating.'</h2>';
    
    echo '<div class="quickstats">
            <div class="content">
                <div class="left">
                    <h3>Average stats</h3>
                    <table>
                        <tr><td class="label">K/D:</td><td class="value"><b>'.colorScale($stats[$t.'_kd'], 1, 1.5).'</b></td><td class="label">A/D:</td><td class="value"><b>'.colorScale($stats[$t.'_ad'], 1.5, 2).'</b>'.'</b></td><td class="label">K+A/D:</td><td class="value"><b>'.colorScale($stats[$t.'_kad'], 2, 3).'</b></td></tr>
                        <tr><td class="label">CK:</td><td class="value"><b>'.colorScale($stats[$t.'_ck'], 50, 100).'</b></td><td class="label">CD:</td><td class="value"><b>'.colorScale($stats[$t.'_cd'], 10, 15).'</b></td><td class="label">Wards:</td><td class="value"><b>'.colorScale($stats[$t.'_wards'], 1, 2).'</b></td></tr>
                        <tr><td class="label">GPM:</td><td class="value"><b>'.colorScale($stats[$t.'_gpm'], 180, 300).'</b></td><td class="label">Exp/min:</td><td class="value"><b>'.colorScale($stats[$t.'_expm'], 250, 400).'</b></td><td class="label">TSR:</td><td class="value"><b>'.colorScale($stats[$t.'_tsr'], 4, 6).'</b></td></tr>
                        <tr><td class="label">Wins:</td><td class="value"><b>'.$stats[$t.'_wins'].'</b></td><td class="label">Losses:</td><td class="value"><b>'.$stats[$t.'_losses'].'</b></td><td class="label">Win %:</td><td class="value"><b>'.colorScale(round($stats[$t.'_winpr']*100, 1), 45, 60).'</b></td></tr>
                    </table>
                </div>
                <div class="clear"></div>
            </div>';
    echo '</div></div>';

    echo '<div class="matches">';
    if($news_id != false)
        echo '<h3 class="title loading">Loading '.count($news_id).' new matches...</h3>';
    else
        echo '<h3 class="title">Matches</h3>';
    //echo '<div class="paginator">'.$paginator.'</div>';
    echo '<table>
            <thead>
                <tr>
                    <th>Hero</th>
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
                    <th>Rating</th>
                </tr>
            </thead>
        <tbody>';
        
    if(isset($matches) && $matches != false)
    {
        foreach($matches as $m)
        {
            $rating = $m['rating'];
            if($rating != 'No stats' && $rating > 0)
                $rating = '+'.$rating;

            $won = $m['won'] ? 'green' : 'red';
            
            echo '<tr class="'.$won.'">
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
    }

    echo '</tbody></table>';
    //echo '<div class="paginator">'.$paginator.'</div>';
    echo '</div>';
        
    if($news_id == false && $matches = false)
        echo '<h3 class="center error">This player hasn\'t played any match in this category.</h3>';
}
else
{
    echo '<div class="notfound center">';
    echo '<h1 class="notfound">Not Found</h1>';
}
?>
</div>

<?php
if($nick != false && $news_id != false)
{
    $str = $news_id[0];
    unset($news_id[0]);

    foreach($news_id as $id)
        $str .= "-$id";
?>
    <script type="text/javascript">
        $(document).ready(function()
        {
            if($('.matches table tbody tr:first').length == 0)
            {
                $('.matches table').hide();
            }
            
            $.get('<?php echo site_url('/jquery/getmatches/'.$stats['nick'].'/'.$str); ?>', function(data) {
                if($('.matches table tbody tr:first').length)
                {
                    $('.matches table tbody tr:first').before(data);
                }
                else
                {
                    $('.matches table tbody').after(data);
                    $('.matches table').show();
                }

                //alert(data);
                $('.loading').text('Matches');
                $('.loading').removeClass('loading');
            });

         });
    </script>
<?php
}
?>