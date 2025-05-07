<?php
/*************************************************************************************/

/******************************************/
/********** GLOBAL CONFIGURATION **********/
/******************************************/

/*
Konstanten werden in PHP mittels der Funktion define() definiert.
Konstanten besitzen im Gegensatz zu Variablen kein $-Präfix
Üblicherweise werden Konstanten komplett GROSS geschrieben.
 */

/********** DATABASE CONFIGURATION **********/
define("DB_SYSTEM", "mysql");
// define("DB_HOST", "access-5017138258.webspace-host.com");
define("DB_HOST", "db5017146812.hosting-data.io");
define("DB_NAME", "dbs13781522");
define("DB_USER", "dbu1374323");
define("DB_PWD", "X!6QdkyvLv8H!a9");

/********** FORMULAR CONFIGURATION **********/
define("MIN_INPUT_LENGTH", 3);
define("MAX_INPUT_LENGTH", 256);

/********** IMAGE UPLOAD CONFIGURATION **********/
define("IMAGE_MAX_WIDTH", 1500);
define("IMAGE_MAX_HEIGHT", 1500);
define("IMAGE_MAX_SIZE", 256 * 1024 * 8);
define("IMAGE_ALLOWED_MIMETYPES", array("image/jpg", "image/jpeg", "image/gif", "image/png"));

/********** STANDARD PATHS CONFIRGURATION **********/
define("IMAGE_UPLOAD_PATH", "uploads/blogimages/");
define("AVATAR_DUMMY_PATH", "../../css/images/avatar_dummy.png");
define("MEDIA_DOWNLOADSPATH", "downloads/media/");

/********** DEBUGGING **********/
define("DEBUG", false); // Debugging for main php document
define("DEBUG_F", false); // Debugging for functions
define("DEBUG_DB", false); // Debugging for db-functions
define("DEBUG_C", false); // Debugging for classes
define("DEBUG_T", false); // Debugging for traits

/*************************************************************************************/
?>