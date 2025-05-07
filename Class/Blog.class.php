<?php
/*******************************************************************************************/

/********************************/
/********** CLASS BLOG **********/
/********************************/
/**
 * Class representing a blog.
 * @extends
 * @interface      Interface iBlog
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
 *    Class represents an Blog
 *
 */
class Blog implements iBlog
{

    /*******************************/
    /********** ATTRIBUTE **********/
    /*******************************/

    // Innerhalb der Klassendefinition müssen Attribute nicht zwingend initialisiert werden
    private $blog_id;
    private $blog_headline;
    private $blog_image;
    private $blog_imageAlignment;
    private $blog_content;
    private $blog_date;
    private $category;
    private $user;
    //private $thema;

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
    public function __construct($blog_id, $blog_headline = null, $blog_image = null, $blog_imageAlignment = null, $blog_content = null, $blog_date = null, $category = null, $user = null)
    {
//if(DEBUG_C)            echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "($blog_id, $blog_headline, $blog_image, $blog_imageAlignment, $blog_content, $blog_date)  (<i>" . basename(__FILE__) . "</i>)</h3>";

        if ($blog_id) {
            $this->setBlog_id($blog_id);
        }

        if ($blog_headline) {
            $this->setBlog_headline($blog_headline);
        }

        if ($blog_image) {
            $this->setBlog_image($blog_image);
        }

        if ($blog_imageAlignment) {
            $this->setBlog_imageAlignment($blog_imageAlignment);
        }

        if ($blog_content) {
            $this->setBlog_content($blog_content);
        }

        if ($blog_date) {
            $this->setBlog_date($blog_date);
        }

        if ($category) {
            $this->setCategory($category);
        }

        if ($user) {
            $this->setUser($user);
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

    /***********************************************************/

    /*************************************/
    /********** GETTER & SETTER **********/
    /*************************************/

    /********** BLOG_ID **********/
    public function getBlog_id()
    {
        return $this->blog_id;
    }
    public function setBlog_id($value)
    {
        $this->blog_id = cleanString($value);
    }

    /********** BLOG_HEADLINE **********/
    public function getBlog_headline()
    {
        return $this->blog_headline;
    }
    public function setBlog_headline($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->blog_headline = cleanString($value);
        }
    }

    /********** BLOG_IMAGE **********/
    public function getBlog_image()
    {
        return $this->blog_image;
    }
    public function setBlog_image($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->blog_image = cleanString($value);
        }
    }

    /********** BLOG_IMAGEALIGMENT **********/
    public function getBlog_imageAlignment()
    {
        return $this->blog_imageAlignment;
    }
    public function setBlog_imageAlignment($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>email</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            //Wenn alles ok ist:
            $this->blog_imageAlignment = cleanString($value);
        }
    }

    /********** BLOG_CONTENT **********/
    public function getBlog_content()
    {
        return $this->blog_content;
    }
    public function setBlog_content($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            //Wenn alles ok ist:
            $this->blog_content = cleanString($value);
        }
    }

    /********** BLOG_DATE **********/
    public function getBlog_date()
    {
        return $this->blog_date;
    }
    public function setBlog_date($value)
    {
        if (!is_string($value) and $value != null) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>email</b> : den Datentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->blog_date = cleanString($value);
        }
    }

    /********** CATEGORY **********/
    public function getCategory()
    {
        return $this->category;
    }
    public function setCategory($value)
    {
        // Auf korrekten Objekttyp prüfen
        if (!$value instanceof Category) {
            if (DEBUG_C) {
                echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: FEHLER: Muss ein Objekt der Klasse 'Category' sein! <i>(" . basename(__FILE__) . ")</i></p>";
            }

        } else {
            $this->category = $value;
        }
    }

    /********** USER **********/
    public function getUser()
    {
        return $this->user;
    }
    public function setUser($value)
    {
        // Auf korrekten Objekttyp prüfen
        if (!$value instanceof User) {
            if (DEBUG_C) {
                echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: FEHLER: Muss ein Objekt der Klasse 'User' sein! <i>(" . basename(__FILE__) . ")</i></p>";
            }

        } else {
            $this->user = $value;
        }
    }

   

    /******************************/
    /********** METHODEN **********/
    /******************************/

    /********** FETCH ALL BLOG-CONTENT FROM DB **********/
    /**
     *
     *    FETCHES A ALL BLOG-CONTENT FROM DB
     *    EITHER VIA THE USR_EMAIL ATTRIBUTE OR THE USR_ID-ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL USER-DATA INTO THE GIVEN USER-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */

    public static function fetchAllBlogFromDb($pdo)
    { /*DEBUG_C*/
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }
        $blogObjectArray = array();
        $sql = "SELECT * FROM blog
										INNER JOIN category USING(cat_id)
                                        INNER JOIN user USING(usr_id)
                                        INNER JOIN thema USING(thema_id)

										ORDER BY blog_date DESC
										LIMIT 10";

        $params = null;
        $statement = $pdo->prepare($sql);

        //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

        $statement->execute($params);
        if (DEBUG_C) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        //Schritt 4 DB: Daten weiterverarbeiten
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

            $user = new User($row['usr_id'], $row['usr_firstname'], $row['usr_lastname'], $row['usr_email'], $row['usr_city'], $row['usr_password']);
            //$user->setUsr_id($row['usr_id']);

            $thema = new Thema($row['thema_id'], $row['thema_name']);

            $category = new Category($row['cat_id'], $row['cat_name'], $thema);
            //$category->setCat_id($row['cat_id']);

            if (DEBUG_C) {
                echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: 'Blog Objekt' wird erstellt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

            $blogObjectArray[] = new Blog($row['blog_id'], $row['blog_headline'], $row['blog_image'], $row['blog_imageAlignment'], $row['blog_content'], $row['blog_date'], $category, $user);
        }
        if (DEBUG_C) {
            echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
        }

        if (DEBUG_C) {
            print_r($blogObjectArray);
        }

        if (DEBUG_C) {
            echo "</pre>";
        }

        return $blogObjectArray;

    }

    /***********************************************************/

    /********** FETCH SINGLE BLOG FROM DB **********/
    /**
     *
     *    FETCHES A SINGLE USER-DATASET FROM DB
     *    EITHER VIA THE USR_EMAIL ATTRIBUTE OR THE USR_ID-ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL USER-DATA INTO THE GIVEN USER-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */
    public static function fetchBlogEntriesByCategoryFromDb($pdo, $cat_id)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $blogObjectArray = array();
        $sql = "SELECT * FROM blog
								 INNER JOIN category USING(cat_id)
								 INNER JOIN user USING(usr_id)
                                 INNER JOIN thema USING(thema_id)
								 WHERE cat_id =?
								 ORDER BY blog_date DESC
								 LIMIT 10
								 ";
        $params = array($cat_id);
        //$params = $cat_id;  
        //Schritt 2 DB: SQL-Statement vorbereiten
        $statement = $pdo->prepare($sql);

        //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

        $statement->execute($params);
        if (DEBUG_C) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        //Schritt 4 DB: Daten weiterverarbeiten
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            //$usr_firstname = NULL, $usr_lastname = NULL, $usr_email = NULL, $usr_city = NULL, $usr_password = NULL
            $user = new User($row['usr_id'], $row['usr_firstname'], $row['usr_lastname'], $row['usr_email'], $row['usr_city'], $row['usr_password']);

            //$category->setCat_id($row['cat_id']);
            $thema = new Thema($row['thema_id'], $row['thema_name']);
            if(DEBUG){
                echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Lade Blog-Einträge aus Thema ... <i>(" . $row['thema_name'] . ")</i></p>";
            }
            

            
            //$user->setUsr_id($row['usr_id']);
            $category = new Category($row['cat_id'], $row['cat_name'], $thema);
           
            
            if (DEBUG) {
                print_r($thema);
            }

            $blogObjectArray[] = new Blog($row['blog_id'], $row['blog_headline'], $row['blog_image'], $row['blog_imageAlignment'], $row['blog_content'], $row['blog_date'], $category, $user);
        }
        if (DEBUG) {
            echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
        }

        if (DEBUG) {
            print_r($blogObjectArray);
        }

        if (DEBUG) {
            echo "</pre>";
        }

        return $blogObjectArray;

    }




/***********************************************************/

    /********** FETCH ALLE  BLOG FOR THE THEMA FROM DB **********/
    /**
     *
     *    FETCHES A SINGLE USER-DATASET FROM DB
     *    EITHER VIA THE USR_EMAIL ATTRIBUTE OR THE USR_ID-ATTRIBUTE
     *    IF DATASET WAS FOUND BUILDS A STATE-OBJECT AND WRITES THE STATE-OBJECT
     *    PLUS ALL USER-DATA INTO THE GIVEN USER-OBJECT
     *
     *    @param    PDO $pdo        DB-Connection object
     *
     *    @return    BOOLEAN        true if dataset was found, else false
     *
     */
    public static function fetchBlogEntriesByThemaFromDb($pdo, $thema_id )
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $blogObjectArray = array();
        $sql = "SELECT * FROM blog
								 INNER JOIN category USING(cat_id)
								 INNER JOIN user USING(usr_id)
                                 INNER JOIN thema USING(thema_id)
								 WHERE thema_id =?
								 ORDER BY blog_date DESC
								 LIMIT 10
								 ";
        $params = array($thema_id);
        //$params = $cat_id;
        //Schritt 2 DB: SQL-Statement vorbereiten
        $statement = $pdo->prepare($sql);

        //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

        $statement->execute($params);
        if (DEBUG_C) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        //Schritt 4 DB: Daten weiterverarbeiten
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            //$usr_firstname = NULL, $usr_lastname = NULL, $usr_email = NULL, $usr_city = NULL, $usr_password = NULL
            $user = new User($row['usr_id'], $row['usr_firstname'], $row['usr_lastname'], $row['usr_email'], $row['usr_city'], $row['usr_password']);

            //$category->setCat_id($row['cat_id']);
            $thema = new Thema($row['thema_id'], $row['thema_name']);
            
            //$user->setUsr_id($row['usr_id']);
            $category = new Category($row['cat_id'], $row['cat_name'], $thema);
            
            

            if (DEBUG_C) {
                echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: 'Blog Objekt' wird erstellt... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

            $blogObjectArray[] = new Blog($row['blog_id'], $row['blog_headline'], $row['blog_image'], $row['blog_imageAlignment'], $row['blog_content'], $row['blog_date'], $category, $user);
        }
        if (DEBUG) {
            echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
        }

        if (DEBUG) {
            print_r($blogObjectArray);
        }

        if (DEBUG) {
            echo "</pre>";
        }

        return $blogObjectArray;

    }





    /***********************************************************/
    /********** SAVES BLOG-OBJECTDATA TO DB **********/
    /**
     *      use in daschboard
     *    SAVES BLOG-OBJECTDATA TO DB
     *    WRITES LAST INSERT ID INTO USER-OBJECT
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

        $sql = "INSERT INTO blog
										(blog_headline, blog_image, blog_imageAlignment, blog_content, cat_id, usr_id)
										VALUES (?, ?, ?, ?, ?, ?)";

        $params = array($this->getBlog_headline(), $this->getBlog_image(), $this->getBlog_imageAlignment(), $this->getBlog_content(), $this->getCategory()->getCat_id(), $this->getUser()->getUsr_id());
        $statement = $pdo->prepare($sql);

        //Schritt 3 DB: SQL-Statement ausführen und gf. Platzhalter füllen

        $statement->execute($params);
        if (DEBUG_C) {
            if ($statement->errorInfo()[2]) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }
        }

        // Schritt 4 DB: Daten weiterverarbeiten
        //$rowCount = $statement->rowCount();
        $newBlogId = $pdo->lastInsertId();
        if (DEBUG) {
            echo "<p class='debug'><b>Line " . __LINE__ . "</b>:\$rowCount: $rowCount  <i>(" . basename(__FILE__) . ")</i></p>\r\n";
        }

        if (!$newBlogId) {
            // Fehlerfall
            $blogmessage = "<p class='error'>Fehler beim Speichern des Beitrags!</p>";

            return false;
        } else {
            // Erfolgsfall
            $this->setBlog_id($newBlogId);

            return true;
        }

    }
    /*********** ************************************************/

}
