<!doctype html>
<html>
<head>
    <title>LPSE</title>
    <link id="favicon" rel="shortcut icon" href="lpse.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="progress">
        <div id="progress_bar"></div>
        <span id="progress_text">0%</span>
    </div>

    <div id="message" class="clearfix">
        <p id="message_text">&nbsp;</p>
        <img id="loading" src="loading.gif" width="16" height="16">
    </div>

    <div id="action">
        <button id="todo" todo="page">Mulai</button>
    </div>

    <div id="data"></div>

    <script>var base_url = '<?php echo $config['base_url']; ?>';</script>
    <script>var base_target_url = '<?php echo $config['base_target_url']; ?>';</script>

    <!-- script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script -->
    <script>window.jQuery || document.write("<script src='js/jquery-1.11.1.min.js'>\x3C/script>")</script>
    <script src="js/functions.js"></script>
</body>
</html>