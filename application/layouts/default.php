<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <meta http-equiv="content-type" 
            content="text/html;charset=utf-8" />
    <?php echo link_tag(css_url('style')); ?>
    <?php echo link_tag(css_url('qTip/jquery.qtip.min')); ?>
    <link rel="shortcut icon" href="<?php echo img_url('favicon.ico'); ?>" />
    <script type="text/javascript" src="<?php echo js_url('jquery-1.6.min'); ?>"></script>
</head>

<body>
    <header>
        <?php echo $header; ?>
    </header>
    
    <div id="wrapper">
        <?php echo $content; ?>
        <div id="push"></div>
    </div>
    
    <footer>
        <?php echo $footer; ?>
    </footer>
</body>
<!-- analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23353031-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript">
    $('[placeholder]').focus(function() {
      var input = $(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
      }
    }).blur(function() {
      var input = $(this);
      if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.addClass('placeholder');
        input.val(input.attr('placeholder'));
      }
    }).blur();
</script>
</html>