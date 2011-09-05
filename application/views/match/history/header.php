<div class="selection">
        <div class="type">
            <ul>
                <li><?php echo anchor(site_url("match/history/$nick/ranked"), 'Ranked', 'title="Ranked stats"'); ?></li>
                <li><?php echo anchor(site_url("match/history/$nick/casual"), 'Casual', 'title="Casual stats"'); ?></li>
                <li><?php echo anchor(site_url("match/history/$nick/public"), 'Public', 'title="Public stats"'); ?></li>
            </ul>
        </div>
        <!--<div class="menu">
            <ul>
                <li><a>Advanced stats</a></li>
                <li><?php echo anchor(site_url("match/history"), 'Match history'); ?></li>
                <li><a>Match viewer</a></li>
            </ul>
        </div>-->
        <div class="field">
        <?php
            if($nick == false)
                $nick = '';
            
            echo form_open('match/playerchange');
            
            echo '<label for="nick">Player </label>';
            echo form_input('nick', $nick, 'id="nick"');
            echo form_submit('ok', 'Query');
            
            echo form_close();
        ?>
        </div>
</div>
    