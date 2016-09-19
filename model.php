<?php

class ShortUrl {

    protected static $chars = "123456789abcdefghijklmnopqrstyvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";  // symbols used for short URL generation 61 chars used atm 
    protected static $table = "Short_urls"; //name of the db
    protected static $checkUrlExists = true;    //change this to false if the verifyUrlExists method is not to be run

    /* Setting up PDO (PHP Data Objects */
    protected $pdo;
    protected $timestamp;

    //connectiong pdo with sql server
    public function __construct() {
        $options = array(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING, PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo = new PDO('mysql:host=localhost;dbname=DBshortURL', 'homestead', 'secret', $options);
        $this->timestamp = date("Y-m-d H:i:s");
    }

    // Check if an url is prompted and if it's
    //  correctly formated and if it exists 
    public function urlToShortCode($url) {
        if (empty($url)) {
            header("Location: error.php");
            exit;
            //throw new Exception("No URL was supplied.");
        }

        if ($this->validateUrlFormat($url) == false) {
            header("Location: error.php");
            exit;

            //echo $url;
            //throw new Exception("URL does not have a valid format.");
        }


        //calls the verifyUrlExists method
        if (self::$checkUrlExists) {
            if (!$this->verifyUrlExists($url)) {
                header("Location: error.php");
                exit;
                //throw new Exception("URL does not appear to exist");
            }
        }

        //If the long url is already in the db, returns the shortcode already given.
        //If the long url is not already in the db, createShortCode method is called
        //before returning the just created shortcode
       
        //  *** REMOVE COMMENT ON THE FOLLOWING CODE TO REENABLE UNIQUE LONG URL IN DB **
        //  //$shortCode = $this->urlExistsInDb($url);
        //if ($shortCode == false) {
            $shortCode = $this->createShortCode($url);
        //}

        return $shortCode;
    }

    protected function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }

    //called only if $checkUrlExists is manually set to true.
    // curl = Client URL Library  */
    // called by UrlToShortCode method
    protected function verifyUrlExists($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);            //use $url as url for the curl
        curl_setopt($ch, CURLOPT_NOBODY, true);         //dont use the body from the URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //dont print result, but return it as $response
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);  //$response set to the HTTP code 
        curl_close($ch);

        return (!empty($response) && $response != 404);     //returns true if the $response var is not empty or 404
    }

    //checks if the long url is already in the db (using pdo)
    //returns either empty case it does not excist. 
    //returns the already asigned shortcode, if it does already excist.
    protected function urlExistsInDb($url) {
        $query = "SELECT short_code FROM " . self::$table .
                " WHERE long_url = :long_url LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array("long_url" => $url);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (empty($result)) ? false : $result["short_code"];
    }

    //called by urlToShortCode if urlExistsInDb comes up empty)
    //set $id to the $id returned from the insertshortcode method
    //calls convertIntToShortCode on the $id
    //calls the insertShortCodeInDb with $id and $shortCode.
    protected function createShortCode($url) {
        $id = $this->insertUrlInDb($url);
        $shortCode = $this->randShortCode($length = 8);
        $this->insertShortCodeInDb($id, $shortCode);
        return $shortCode;
    }

    //called by the createShortCode method
    //returns the id used for $id used in createShortCode method
    protected function insertUrlInDb($url) {
        $query = "INSERT INTO " . self::$table .
                " (long_url , date_created) " .
                "VALUES (:long_url, :timestamp)";
        $stmnt = $this->pdo->prepare($query);
        $params = array("long_url" => $url,
            "timestamp" => $this->timestamp);
        $stmnt->execute($params);

        return $this->pdo->lastInsertId();
    }

    //uses $id generated by the createShortCode method

    function randShortCode($length = 8) {
        $charactersLength = strlen(self::$chars);
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= self::$chars[rand(0, $charactersLength - 1)];
        }

        $query = "SELECT id, long_url FROM " . self::$table .
                " WHERE short_code COLLATE utf8_bin = :short_code LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "short_code" => $code);
        $stmt->execute($params);
        $result = $stmt->fetch();

        if (!empty($result)) {
            randShortCode($length = 8);
        } else {
            return $code;
        }
    }

    /*
      protected function convertIntToShortCode($id) {
      $id = intval($id);  //ensures the id is an int and not a string
      if ($id < 1) {
      throw new Exception(
      "The ID is not a valid integer");
      }

      $length = strlen(self::$chars);
      //make sure length of avaiable character is at
      //least a reasonable minimum - there should be at
      // least 10 characters (using 61 now, but this can be changed to a min of 10)
      //currently using
      if ($length < 10) {
      throw new Exception("Length of chars is too small");
      }

      //returns $code as a base 61 version of the id from the db.
      $code = "";
      while ($id > $length - 1) {
      //determine the value of the next higher character
      // in the short code should be and prepend
      $code = self::$chars[fmod($id, $length)] .
      $code;
      // reset $id to remaining value to be converted
      $id = floor($id / $length);
      }

      //remaining value of $id is less than the length of
      //self::$chars
      $code = self::$chars[$id] . $code;

      return $code;
      }
     * 
     */

    //called by the createShortCode method
    protected function insertShortCodeInDb($id, $code) {
        if ($id == null || $code == null) {
            throw new Exception("Input parameter(s) invalid");
        }
        $query = "UPDATE " . self::$table .
                " SET short_code =:short_code WHERE id = :id";
        $stmnt = $this->pdo->prepare($query);
        $params = array("short_code" => $code, "id" => $id);

        $stmnt->execute($params);

        if ($stmnt->rowCount() < 1) {
            throw new Exception("Row was not updated with short code.");
        }

        return true;
    }

    //used from index/handler to convert a shortcode to a functioning url
    public function shortCodeToUrl($code, $increment = true) {
        if (empty($code)) {
            throw new Exception("No short code was supplied.");
        }

        if ($this->validateShortCode($code) == false) {
            throw new Exception("Short code does not have a valid format.");
        }

        $urlRow = $this->getUrlFromDb($code);

        if (empty($urlRow)) {
            header("Location: error2.php");
            exit;

            //throw new Exception("Short code does not appear to excist,");
        }


        if ($increment == true) {
            $this->incrementCounter($urlRow["id"]);
        }

        return $urlRow["long_url"];
    }

    protected function validateShortCode($code) {
        return preg_match("|[" . self::$chars . "]+|", $code);
    }

    protected function getUrlFromDb($code) {
        $query = "SELECT id, long_url FROM " . self::$table .
                " WHERE short_code COLLATE utf8_bin = :short_code AND is_expired IS NOT TRUE LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "short_code" => $code);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return (empty($result)) ? false : $result;
    }

    protected function incrementCounter($id) {
        $query = "UPDATE " . self::$table .
                " SET counter = counter +1 WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "id" => $id);
        $stmt->execute($params);
    }

    public function expireDb() {
        $this->setExpirationDates();

        $this->pdo->exec("UPDATE " . self::$table . " SET is_expired = '1' 
            WHERE expiration_date < NOW();");

//        $query = "UPDATE " . self::$table .
//                    " SET is_expired =1 WHERE expiration_date > NOW() AND is_expired IS NOT 1";
//            $stmnt = $this->pdo->prepare($query);
//            $params = array("expiration_date" => $expDate, "id" => $expId);
//
//            $stmnt->execute($params);
        //do sql stuff to set expired = true (1) for all currently not expired entries where expiration_date > now
        // return nothing
    }

    protected function setExpirationDates() {

        //sets the expirationdates calculated into the $expirationDatetime array
        //for each id contained in the array using the corresponding datetime
        // use $this->getExpirationDates()
        //returns nothing

        foreach ($this->getExpirationDates() as $expDateId) {
            $expDate = $expDateId[0]->format('Y-m-d H:i:s');
            $expId = $expDateId[1];
            $query = "UPDATE " . self::$table .
                    " SET expiration_date = :expiration_date WHERE id = :id";
            $stmnt = $this->pdo->prepare($query);
            $params = array("expiration_date" => $expDate, "id" => $expId);
            $stmnt->execute($params);
        }
    }

    protected function getExpirationDates() {
        $date_created = "";
        $query = "SELECT id, date_created, duration FROM " . self::$table .
                " WHERE duration IS NOT NULL AND is_expired IS NOT TRUE";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "date_created" => $date_created);
        $stmt->execute($params);
        $result = $stmt->fetchAll();


        //change the two dimensional $result array from containing strings for all values
        //to a new two dimensional array $datetimeDuration containing "date_created" as a datetime and "duration" as an int.


        $datetimeDuration = array();

        $resultSize = sizeof($result);
        for ($i = 0; $i < $resultSize; $i++) {
            array_push($datetimeDuration, array(date_create_from_format('Y-m-d H:i:s', $result[$i][1]), intval($result[$i][2]), $result[$i][0]));
        }


        //adds the "duration" (in minutes) to  "date_created" for each entry resulting in a two dim. array with expiration dates and id
        $expirationDatetime = array();
        for ($i = 0; $i < $resultSize; $i++) {
            $DI = new DateInterval('PT' . $datetimeDuration[$i][1] . 'M');
            date_add($datetimeDuration[$i][0], $DI);
            array_push($expirationDatetime, array($datetimeDuration[$i][0], $datetimeDuration[$i][2]));
        }
        return $expirationDatetime;
    }
    
    public function setDuration($duration,$shortcode){
        
        $query = "UPDATE " . self::$table .
                " SET duration =:duration WHERE short_code = :short_code";
        $stmnt = $this->pdo->prepare($query);
        $params = array("short_code" => $shortcode, "duration" => $duration);

        $stmnt->execute($params);
    }

}
