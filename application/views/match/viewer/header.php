<div class="selection">
        <div class="menu">
            <ul>
                <li><a>Advanced stats</a></li>
                <li><?php echo anchor(site_url("match/history"), 'Match history'); ?></li>
                <li><a>Match viewer</a></li>
            </ul>
        </div>
        <div class="field">
        <?php
            echo form_open('match/matchchange');
            
            echo '<label for="nick">Match </label>';
            echo form_input('match', ($mid == -1? '' : $mid), 'id="match"');
            echo form_submit('ok', 'Query');
            
            echo form_close();
        ?>
        </div>
</div>