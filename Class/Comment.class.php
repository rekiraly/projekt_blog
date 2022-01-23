<?php
class Comment implements iComment
{
    private $com_id;
    private $com_inhalt;
    private $usr_id;
    private $cat_id;

    public function __construct($com_id = null, $com_inhalt = null, $usr_id = null, $cat_id = null)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "($com_id, $com_inhalt, $usr_id, $cat_id)  (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        if ($com_id) {
            $this->setCom_id($com_id);
        }

        if ($com_inhalt) {
            $this->setCom_inhalt($com_inhalt);
        }

        if ($usr_id) {
            $this->setUsr_id($usr_id);
        }

        if ($cat_id) {
            $this->setCat_id($cat_id);
        }

        if (DEBUG_C) {
            echo "<pre class='debugClass'><b>Line  " . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";
        }

        if (DEBUG_C) {
            print_r($this);
        }

        if (DEBUG_C) {
            echo "</pre>";
        }

    }

    public function getCom_id()
    {
        return $this->com_id;
    }

    public function setCom_id($value)
    {
        $this->com_id = cleanString($value);
    }

    public function getCom_inhalt()
    {
        return $this->com_inhalt;
    }

    public function setCom_inhalt($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->com_inhalt = cleanString($value);
        }
    }

    public function getUsr_id()
    {
        return $this->usr_id;
    }

    public function setUsr_id($value)
    {
        $this->usr_id = cleanString($value);
    }

    public function getCat_id()
    {
        return $this->cat_id;
    }

    public function setCat_id($value)
    {
        $this->cat_id = cleanString($value);
    }

    public function fetchFromDb($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $sql = "SELECT * FROM Commentary";
        $params = null;
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        if (DEBUG) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            //Fehlerfall
            return false;

        } else {
            //Erfolgsfall

            //User-Objekt erzeugen

            $this->setCom_id($row['com_id']);
            $this->setCom_inhalt($row['com_inhalt']);
            $this->setUsr_lastname($row['usr_id']);
            $this->setUsr_city($row['cat_id']);

            if (DEBUG_C) {
                echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";

                print_r($this);

                echo "</pre>";
            }

            return true;
        }
    }

    /***********************************************************/
    /********** SAVES COMMENARY-OBJECTDATA TO DB **********/
    /**
     *
     *    SAVES COMMENT-OBJECTDATA TO DB
     *    WRITES LAST INSERT ID INTO COMMENT-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if writing was successful, else false
     *
     */
    public function saveToDb($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $sql = "INSERT INTO Commentary
									(com_inhalt, usr_id, cat_id)
									VALUES (?, ?, ?)";
        $params = array(
            $this->getCom_inhalt(),
            $this->getUsr_id(),
            $this->getCat_id(),
        );
        // Schritt 2 DB: SQL-Statement vorbereiten

        $statement = $pdo->prepare($sql);

        // Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
        $statement->execute($params);
        if (DEBUG) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        //Schritt 4 DB: Daten weiterverarbeiten
        $rowCount = $statement->rowCount();
        if (DEBUG) {
            echo "<p class='debug'><b>Line " . __LINE__ . "</b>:\$rowCount: $rowCount  <i>(" . basename(__FILE__) . ")</i></p>\r\n";
        }

        if (!$rowCount) {
            // Fehlerfall

            return false;
        } else {
            // Erfolgsfall
            $this->setCom_id($pdo->lastInsertId());

            return true;
        }
    }

}
