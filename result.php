<?php
session_start();
        if (isset($_SESSION['hResultShort'])) {
            $a = $_SESSION['hResultShort'];
        } else {
            $a = null;
        }

        if (isset($_SESSION['hResultLong'])) {
            $b = $_SESSION['hResultLong'];
        } else {
            $b = null;
        }
        //unset($_SESSION["hResultShort"]);
        //unset($_SESSION["hResultLong"]);
?>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <title>Url Shortener</title>
    <script src="jquery-3.1.0.min.js"></script>
    <script src="script.js"></script>
</head>

<body>
    <div id="header">
        <div class="output">
            <div id="outputShort"><p>This is your new short-url:</p>
            	<input type="text" id="shorturl" value=<?php
                	if (isset($a)) {
                    echo $a;
                	}
                	?> >
            </div>
            <div id="setDuration">
            	<p>Set duration for this short Url in minutes:</p>
            	<form action = "handle.php" method="POST">
                    <input type ="number" name="duration" min="1">
                    <input type="submit" value="Set">
                </form>
            </div>
            <div id="outputLong">
            	<P>For this long url</P>
            	<h2 id='longurl'> 
                	<?php
                	if (isset($b)) {
                    	echo $b;
                	}
                	?>
            	</h2>
            </div>
        </div>


    </div>
    <div id="footer">
        <div id="buttons">
        	<div class="btnContainer">
            	<button data-copytarget="#shorturl">Copy to Clipboard</button>
            	<p>Copy the created short-Url into the clipboard, because ctrl + c is hard</p>
            </div>
            <div class="btnContainer">
            	<a href="../index.php"><button id="homeBtn">Make another short-link</button></a>
            	<p>Back to the frontpage, so you can make more awesome short-urls
            </div>
            <div class="btnContainer">
            	<button id="durationBtn">Set Link Duration</button>
            	<p>Set a duration for expiring this short-url. This is OPTIONAL.</p>
            </div>
        </div>

    </div>

</body>
