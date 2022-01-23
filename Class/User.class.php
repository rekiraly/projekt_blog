<?php
class User implements iUser
{
    private $usr_id;
    private $usr_firstname;
    private $usr_lastname;
    private $usr_email;
    private $usr_city; //          убрать

    private $usr_password;

    public function __construct($usr_id = null, $usr_firstname = null, $usr_lastname = null, $usr_email = null, $usr_city = null, $usr_password = null)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "($usr_id, $usr_firstname, $usr_lastname, $usr_email, $usr_city, $usr_password )  (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        if ($usr_id) {
            $this->setUsr_id($usr_id);
        }

        if ($usr_firstname) {
            $this->setUsr_firstname($usr_firstname);
        }

        if ($usr_lastname) {
            $this->setUsr_lastname($usr_lastname);
        }

        if ($usr_email) {
            $this->setUsr_email($usr_email);
        }

        if ($usr_city) {
            $this->setUsr_city($usr_city);
        }

        if ($usr_password) {
            $this->setUsr_password($usr_password);
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

    public function getUsr_id()
    {
        return $this->usr_id;
    }

    public function setUsr_id($value)
    {
        $this->usr_id = cleanString($value);
    }

    public function getUsr_firstname()
    {
        return $this->usr_firstname;
    }

    public function setUsr_firstname($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->usr_firstname = cleanString($value);
        }
    }

    public function getUsr_lastname()
    {
        return $this->usr_lastname;
    }

    public function setUsr_lastname($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->usr_lastname = cleanString($value);
        }
    }

    public function getUsr_email()
    {
        return $this->usr_email;
    }

    public function setUsr_email($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>email</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            //Vor dem Schreiben gültige email adresse prüfen
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>email</b> : Es ist keine gültige email adresse! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->usr_email = cleanString($value);
        }
    }

    public function getUsr_city()
    {
        return $this->usr_city;
    }

    public function setUsr_city($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->usr_city = cleanString($value);
        }
    }

    public function getUsr_password()
    {
        return $this->usr_password;
    }

    public function setUsr_password($value)
    {
        if (!is_string($value)) {
            if (DEBUG_C) {
                echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: <b>usr_id</b> : denDatentype muss 'String' sein! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
            }

        } else {
            $this->usr_password = cleanString($value);
        }
    }

    public function getFullname()
    {
        return $this->getUsr_firstname() . " " . $this->getUsr_lastname();
    }

    public function fetchFromDb($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $sql = "SELECT * FROM User WHERE usr_email = ? OR usr_id = ?";
        $params = array($this->getUsr_email(), $this->getUsr_id());
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

            $this->setUsr_id($row['usr_id']);
            $this->setUsr_firstname($row['usr_firstname']);
            $this->setUsr_lastname($row['usr_lastname']);
            $this->setUsr_city($row['usr_city']);
            $this->setUsr_password($row['usr_password']);

            if (DEBUG_C) {
                echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";

                print_r($this);

                echo "</pre>";
            }

            return true;
        }
    }

    public function emailExists($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        if (!$this->getUsr_id()) {
            $this->setUsr_id("invalid");
        }
        // <=>: NULL-Safe-Vergleichsoperator MySQL | "NOT usr_id <=> ? ( usr_id != ?)"
        // für profile-Seite: Damit die eigene Email-Adresse von der Prüfung, ob die
        // Adresse bereits von jemand Anderem in der DB registriert wurde, ausgenommen
        // wird, muss mittels der User-ID der eigene Datansatz von der SQL-Abfrage
        // ausgenommen werden.
        // Damit aber auch die Registrierungsseite nach wie vor funktioniert, über den
        // normalen Vergleichsoperator in SQL jedoch keine Prüfung gegen NULL möglich ist,
        // muss hier der sog. NULL-safe Vergleichsoperator <=> benutzt werden, der Vergleiche
        // gegen NULL ermöglicht.
        // Da der NULL-safe Vergleichsoperator keine Verneinung kennt (!=), muss auf =
        // geprüft und der gesamte Ausdruck mittels NOT negiert werden.

        $sql = "SELECT COUNT(usr_email) FROM User
								WHERE usr_email = ?
								AND NOT usr_id <=> ?
								";

        $params = array(
            $this->getUsr_email(),
            $this->getUsr_id(),
        );

        //Schritt 2 DB: SQL-Statement vorbereiten
        /*if($this->getUsr_id()){
        $sql . = "AND usr_id !=?"
        $params[] = $this->getUsr_id();
        }
         */
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
        $anzahl = $statement->fetchColumn();
        if (DEBUG) {
            echo "<p class='debug'><b>Line " . __LINE__ . "</b>: \$anzahl: $anzahl <i>(" . basename(__FILE__) . ")</i></p>\r\n";
        }

        return $anzahl;
    }

    public function saveToDb($pdo)
    {
        if (DEBUG_C) {
            echo "<h3 class='debugClass'><b>Line  " . __LINE__ . "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
        }

        $sql = "INSERT INTO User
									(usr_firstname, usr_lastname, usr_email, usr_password)
									VALUES (?, ?, ?, ?)";
        $params = array(
            $this->getUsr_firstname(),
            $this->getUsr_lastname(),
            $this->getUsr_email(),
            $this->getUsr_password(),

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
            $this->setUsr_id($pdo->lastInsertId());

            return true;
        }
    }
}
