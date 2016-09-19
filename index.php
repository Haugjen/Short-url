<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <script src="jquery-3.1.0.min.js"></script>
        <script src="script.js"></script>

        <title>Url Shortener</title>
    </head>
    <body>
        <?php
        include 'handle.php';
        //use this to check if the shortlink excists in the db!
         //expire rows out of duration in the db
         $class = new ShortUrl();
         $class->expireDB();
        
        $pathList = array("/", "/index.php", "/handle.php", "/error.php", "/result.php", "/error2.php", "/userpanel.php");  //list of path exceptions, that will not be linked off externaly
        if (!in_array($_SERVER['REQUEST_URI'], $pathList)) {
            $indexcode = substr($_SERVER['REQUEST_URI'], 1);
            $classI = new ShortUrl();
            $link = $classI->shortCodeToUrl($indexcode);
        }

        function redirect($link) {
            ob_start();
            header('Location: ' . $link);
            ob_end_flush();
            die();
        }

        if (isset($link)) {
            redirect($link);
        }

        //get the longURL and shortUrl from Session in handle.php
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
        unset($_SESSION["hResultShort"]);
        unset($_SESSION["hResultLong"]);


       
        ?>
        <div id="header">
            <div id="indexHeader"><p>ENTER AN URL TO BE SHORTENED HERE:</p></div>
        </div>
        <div id="shorten">
                <form action = "handle.php" method="POST">
                    <input type ="text" name="shorten">
                    <input type="submit" value="Shorten">
                </form>
            </div>
        <div id="footer">
            <!-- <div class="output" 
            <?php
            if (($a == null)) {
                echo 'id="hide"';
            }
            ?>
                 >

                <p>This is your new short-url:</p>
                <h2 id='shorturl'>
                    <?php
                    if (isset($a)) {
                        echo $a;
                    }
                    ?>
                </h2>
                <P>For this long url</P>
                <h2 id='longurl'> 
                    <?php
                    if (isset($b)) {
                        echo $b;
                    }
                    ?>
                </h2>
            </div> -->
        </div>
    </body>
</html>
