<?php
session_start();
unset($_SESSION["hResultShort"]);
unset($_SESSION["hResultLong"]);
session_destroy();
?>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <title>Url Shortener</title>
</head>

<body>
    <div id="header">
        <div id="eMsg">
            <p>This shorturl was not found in the database!<br />If the creator set a duration, it may have expired.</p>
            <p></p>
        </div>
    </div>
    <div id="footer">
        <div id="buttons">
            <div class="btnContainer" id="eBtnContainer">
                <a href="../index.php"><button >Make new short URL</button></a>
                    <p>If you know the intended long URL, you can remake it with this button</p>
            </div>
        </div>
    </div>

</body>


