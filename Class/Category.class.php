<?php
/*******************************************************************************************/

/********************************/
/********** CLASS CATEGORY **********/
/********************************/
/**
 * Class representing a user.
 * @extends
 * @interface      Interface iCategory
 */

/*
Die Klasse ist quasi der Bauplan/die Vorlage für alle Objekte, die aus ihr erstellt werden.
Sie gibt die Eigenschaften/Attribute eines späteren Objekts vor (Variablen) sowie
die "Handlungen" (Methoden/Funktionen), die das spätere Objekt vornehmen kann.

Jede Objekt einer Klasse ist nach dem gleichen Schema aufgebaut (gleiche Eigenschaften und Methoden),
besitzt aber i.d.R. unterschiedliche Werte (Variablenwerte).
 */

/*******************************************************************************************/

/**
 *
 *    Class represents an User
 *
 */
class Category implements iCategory
{

    /*******************************/
    /********** ATTRIBUTE **********/
    /*******************************/

    // Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden
    private $cat_id;
    private $cat_name;
    private $thema;

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
    public function __construct($cat_id = null, $cat_name = null, $thema = null)
    {

        if (DEBUG) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "($cat_id, $cat_name)  (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        if ($cat_id) {
            $this->setCat_id($cat_id);
        }

        if ($cat_name) {
            $this->setCat_name($cat_name);
        }
        if ($thema) {

            $this->setThema($thema);
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

    /********** CAT_ID **********/
    public function getCat_id()
    {
        return $this->cat_id;
    }
    public function setCat_id($value)
    {
        $this->cat_id = cleanString($value);
    }

    /********** CAT_NAME **********/
    public function getCat_name()
    {
        return $this->cat_name;
    }
    public function setCat_name($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>cat_name</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->cat_name = cleanString($value);
        }
    }

    /********** CAT_THEMA **********/
    public function getThema()
    {
        return $this->thema;
    }
    public function setThema($value)
    {
        if (!$value instanceof Thema) {
            if (DEBUG_C) {
                echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: FEHLER: Muss ein Objekt der Klasse 'Thema' sein! <i>(" . basename(__FILE__) . ")</i></p>";
            }

        } else {
            $this->thema = $value;
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
    public function catExists($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $anzahl = null;
        if (!$this->getCat_name()) {
            //$this->setCat_name("invalid");
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER:Es gibt keine Kategoriename! <i>(" . basename(__FILE__) . ")</i></p>";
            }

        } elseif (!$this->getThema()->getThema_id()) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: FEHLER:Es gibt keine Themaid! <i>(" . basename(__FILE__) . ")</i></p>";
            }
        } else {

            $sql = "SELECT COUNT(*) FROM Category
									WHERE cat_name = ? AND thema_id = ?
									";

            $params = array($this->getCat_name(), $this->getThema()->getThema_id());

            $statement = $pdo->prepare($sql);

            //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

            $statement->execute($params);
            if (DEBUG) {
                if ($statement->errorInfo()[2]) {
                    echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
                }
            }

            //Schritt 4 DB: Daten weiterverarbeiten
            $anzahl = $statement->fetchColumn();
            if (DEBUG) {
                echo "<p class='debug'><b>Line " . __LINE__ . "</b>: \$anzahl: $anzahl <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

            return $anzahl;

        }

    }

    /***********************************************************/

    /********** SAVES CATEGORY-OBJECTDATA TO DB **********/
    /**
     *
     *    SAVES CATEGORY-OBJECTDATA TO DB
     *    WRITES LAST INSERT ID INTO CATEGORY-OBJECT
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

        $sql = "INSERT INTO Category
									(cat_name, thema_id)
									VALUES (?, ?)";
        $params = array($this->getCat_name(), $this->getThema()->getThema_id());
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
            $this->setCat_id($pdo->lastInsertId());

            return true;
        }
    }

    /***********************************************************/
    /********** FETCH ALL CATEGORY FOR THE THEMA FROM DB **********/
    /**
     *
     *    FETCHES A CATEGORY FOR THE THEMES FROM DB
     *    EITHER VIA THE CAT_ID ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL USER-DATA INTO THE GIVEN USER-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */
    public static function fetchAllCategoriesFromDb($pdo, $thema_id) //nur für eines Thema

    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }
       // $categoriesArray[] = null;
        $sql = "SELECT * FROM category INNER JOIN thema USING(thema_id) WHERE thema_id=?";
        $params = array($thema_id);
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

            $thema = new Thema($row['thema_id'], $row['thema_name']);
            $categoriesArray[] = new Category($row['cat_id'], $row['cat_name'], $thema);

        }
               // echo "<pre>";
                //print_r( $categoriesArray);
                //echo "</pre>";
        //$categoriesArray = $statement->fetchAll();

        // Prüfen, ob ein Datensatz zurückgeliefert wurde
        // Wenn ein Datensatz zurückgeliefert wurde, muss die Login-Email korrekt sein

        //верни класс!!!y
        return $categoriesArray;

    }

    /********** FETCH SINGLE CATEGORY FROM DB  FOR THE THEMA**********/
    /**
     *
     *    FETCHES A SINGLE CATEGORY-DATASET FROM DB
     *    EITHER VIA THE CAT_ID ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL CATEGORY-DATA INTO THE GIVEN CATEGORY-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */
    public function fetchCategoryFromDb($pdo, $thema_id) /**одну категорию по выбранной теме */
    {
        if ($this->getThema()->getThema_id() != $thema_id) {
            if (true) {
                echo "<h3 class='debug err'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
            }
            return false;
        } else {

            if (DEBUG_C) {
                echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
            }

            $sql = "SELECT * FROM Category INNER JOIN thema USING(thema_id)
                                     WHERE cat_id =?
                                    ";
            $params = array($this->getCat_id());
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
                //Erfolgsfall

                //Category-Objekt erzeugen
                $thema = new Thema($row['thema_id'], $row['thema_name']);
                $this->setCat_id($row['cat_id']);
                $this->setCat_name($row['cat_name']);
                $this->setThema($thema);

                //if (DEBUG) {
                echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
                // }

                //if (DEBUG) {
                print_r($this);
                //}

                //if (DEBUG) {
                echo "</pre>";
                // }

                return true;
            }
        }
    }

    /********** FETCH THEMA FROM DB **********?????????????????????????**/
    /**
     *
     *    FETCHES A THEMA FROM DB
     *    EITHER VIA THE _ID ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL USER-DATA INTO THE GIVEN USER-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */
    public static function fetchAllThemenFromDb($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $sql = "SELECT thema_id FROM category";
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

            $themenArray[] = $row['thema_id'];

        }

        //$categoriesArray = $statement->fetchAll();

        // Prüfen, ob ein Datensatz zurückgeliefert wurde
        // Wenn ein Datensatz zurückgeliefert wurde, muss die Login-Email korrekt sein

        //верни класс!!!y
        return $themenArray;

    }

}
