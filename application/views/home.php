<div id="home">
    <div id="searchbox">
        <div id="logo"><?php echo img(img_url('search_home.png')); ?></div>
        <div id="input">
            <?php
                echo form_open('match/playerchange');
                
                $placeholder = "Nickname";
                $jq = "";
                if(strlen($nick)> 0)
                {
                    $placeholder = $nick.' not found';
                    $jq = 'onclick="$(this).attr(\'placeholder\', \'Nickname\');"';
                }
                echo form_input('nick', "", 'id="nick" placeholder="'.$placeholder.'" '.$jq);
                echo form_close();
            ?>
        </div>
    </div>
</div>