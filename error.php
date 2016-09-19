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
            <p>There was a problem with the given URL:<br />No short URL was created!</p>
            <p></p>
        </div>
    </div>
    <div id="footer">
        <div id="buttons">
            <div class="btnContainer" id="eBtnContainer">
                <a href="../index.php"><button >Try Again</button></a>
                    <p>Make sure that a valid long URL is given. The best way to enter the long URL is to copy-paste <span id="impact">the entire</span> long URL.</p>
            </div>
        </div>
    </div>

</body>


