<!doctype html>
<html>
<head>
</head>
<body>
    <p>Ditemukan 10 data lelang</p>

    <table>
        <thead>
            <tr>
                <th><input id="checkall" name="checkall" type="checkbox" value="checkall"></th>
                <th>ID Lelang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input name="ids[]" type="checkbox" value="1"></td>
                <td>801231</td>
                <td>Lelang sudah selesai</td>
            </tr>
            <tr>
                <td><input name="ids[]" type="checkbox" value="2"></td>
                <td>801232</td>
                <td>Lelang sudah selesai</td>
            </tr>
        </tbody>
    </table>
    <script>var base_url = '<?php echo $config['base_url']; ?>';</script>
    <script>var base_target_url = '<?php echo $config['base_target_url']; ?>';</script>

    <!-- script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script -->
    <script>window.jQuery || document.write("<script src='js/jquery-1.11.1.min.js'>\x3C/script>")</script>
    <script src="js/functions.js"></script>
</body>
</html>

<!-- doctype html>
<html>
<head>
    <title>LPSE</title>
    <link id="favicon" rel="shortcut icon" href="lpse.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="message">
        <span>Tekan tombol 'Mulai' untuk menghitung halaman yang harus ditelusuri.</span>
        <img src="loading.gif" width="16" height="16">
    </div>
    <div style="clear: both"></div>

    <div id="progress">
        <div></div>
        <p><span>0%</span></p>
    </div>

    <div id="tool">
        <button id="todo" data-todo="page">Mulai</button>
    </div>

    <div id="result"></div>
</body>
</html -->