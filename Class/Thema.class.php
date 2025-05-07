<?php

/**
 *
 *    Class represents an Thema
 *
 */
class Thema implements iThema
{

    /*******************************/
    /********** ATTRIBUTE **********/
    /*******************************/

    // Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden
    private $thema_id;
    private $thema_name;

    /***********************************************************/

    /*********************************/
    /********** KONSTRUKTOR **********/
    /*********************************/

    /*
    Der Konstruktor erstellt eine neue Klasseninstanz/Objekt.
    Soll ein Objekt beim Erstellen bereits mit Attributwerten versehen werden,
    muss ein eigener Konstruktor geschrieben werden. Dieser nimmt die Werte in
    Form von Parametern    (genau wie bei Funktionen) entgegen und ruft seinerseits
    die entsprechenden Setter auf, um die Werte zuzuweisen.
     */

    /**
     *
     *    Calls constructor from "traits/autoConstruct.trait.php"
     *
     */
    public function __construct($thema_id = null, $thema_name = null)
    {

        if (DEBUG) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "($thema_id, $thema_name)  (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        if ($thema_id) {
            $this->setThema_id($thema_id);
        }

        if ($thema_name) {
            $this->setThema_name($thema_name);
        }

        if (DEBUG) {
            echo "<pre class='debugClass'><b>Line  " . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";

            print_r($this);

            echo "</pre>";
        }
    }

    /***********************************************************/

    /*************************************/
    /********** GETTER & SETTER **********/
    /*************************************/

    /********** THEMA_ID **********/
    public function getThema_id()
    {
        return $this->thema_id;
    }
    public function setThema_id($value)
    {
        $this->thema_id = cleanString($value);
    }

    /********** THEMA_NAME **********/
    public function getThema_name()
    {
        return $this->thema_name;
    }
    public function setThema_name($value)
    {
        //echo "TEST " . $value;
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>thema_name</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->thema_name = cleanString($value);
        }
    }

    /********** VIRTUELLE ATTRIBUTE **********/
    /**
     *
     *    returns the combined fullname (firstname lastname) of an User
     *
     */

    /***********************************************************/

    /******************************/
    /********** METHODEN **********/
    /******************************/

    /********** CHECKS IF CAT-NAME EXISTS IN DB EITHER BY NAME OR BY ID **********/
    /**
     *
     *    CHECKS IF A GIVEN CATEGORY FOR THE THEMA EXISTS  IN DB
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    INT            Number of matching DB entries
     *
     */
    public function themaExists($pdo) /**!!!!!!!!! */
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $anzahl = null;
        if (!$this->getThema_name()) {
            //$this->setCat_name("invalid");
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER:Es gibt keine Thema name! <i>(" . basename(__FILE__) . ")</i></p>";
            }

        } else {

            $sql = "SELECT * FROM thema
									WHERE thema_name = ?
									";

            $params = array($this->getThema_name());

            $statement = $pdo->prepare($sql);

            //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

            $statement->execute($params);
            if (DEBUG_C) {
                if ($statement->errorInfo()[2]) {
                    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
                }
            }

            //Schritt 4 DB: Daten weiterverarbeiten
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            //$anzahl = $statement->fetchColumn();

            /**test test test */

            if (!$row) {
                return false;
            } else {
                //$row = $statement->fetch(PDO::FETCH_ASSOC);

                $this->setThema_id($row['thema_id']);

                if (DEBUG) {
                    echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
                }

                if (DEBUG) {
                    print_r($this);
                }

                if (DEBUG) {
                    echo "</pre>";
                }

            }

            return true;
            //return $row['thema_id'];

        }

    }

    /***********************************************************/

    /********** SAVES THEMA-OBJECTDATA TO DB **********/
    /**
     *
     *    SAVES THEMA-OBJECTDATA TO DB
     *    WRITES LAST INSERT ID INTO THEMA-OBJECT
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

        $sql = "INSERT INTO thema
									(thema_name)
									VALUES (?)";
        $params = array($this->getThema_name());
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
            $this->setThema_id($pdo->lastInsertId());

            return true;
        }
    }

    /***********************************************************/
    /********** FETCH THEMA FROM DB **********/
    /**
     *
     *    FETCHES A THEMES FROM DB
     *    EITHER VIA THE THEMA_ID ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL USER-DATA INTO THE GIVEN USER-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */
    public static function fetchAllThemesFromDb($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $sql = "SELECT * FROM thema";
        $params = null;
        //Schritt 2 DB: SQL-Statement vorbereiten
        $statement = $pdo->prepare($sql);
        //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

        $statement->execute($params);
        if (DEBUG) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        // oder mit Ternärem Operator:
        //Schritt 4 DB: Daten weiterverarbeiten
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

            //echo "thema_name " . $row['thema_name'];
            $themesArray[] = new Thema($row['thema_id'], $row['thema_name']);

        }
        // echo $themesArray[0]->getThema_name();

        // Prüfen, ob ein Datensatz zurückgeliefert wurde
        // Wenn ein Datensatz zurückgeliefert wurde, muss die Login-Email korrekt sein

        //верни класс!!!y
        return $themesArray;

    }

    /********** FETCH SINGLE THEME FROM DB **********/
    /**
     *
     *    FETCHES A SINGLE THEME-DATASET FROM DB
     *    EITHER VIA THE THEME_ID ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL THEMES-DATA INTO THE GIVEN THEME-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */
    public function fetchThemaFromDb($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $sql = "SELECT * FROM thema
								 WHERE thema_id =?
								 ";
        $params = array($this->getThema_id());
        //Schritt 2 DB: SQL-Statement vorbereiten
        $statement = $pdo->prepare($sql);

        //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

        $statement->execute($params);
        if (DEBUG) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        // oder mit Ternärem Operator:
        //Schritt 4 DB: Daten weiterverarbeiten
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        // Prüfen, ob ein Datensatz zurückgeliefert wurde
        // Wenn ein Datensatz zurückgeliefert wurde, muss die Login-Email korrekt sein
        if (!$row) {
            //Fehlerfall
            return false;

        } else {
            if ($this->getThema_id() == null) {
                $this->setThema_id($row['thema_id']);
            }

            //Erfolgsfall

            //Category-Objekt erzeugen

            $this->setThema_name($row['thema_name']);

            if (DEBUG) {
                echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
            }

            if (DEBUG) {
                print_r($this);
            }

            if (DEBUG) {
                echo "</pre>";
            }

            return true;
        }
    }

}
