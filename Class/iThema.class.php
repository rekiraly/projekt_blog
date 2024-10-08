<?php
/*******************************************************************************************/

/*************************************/
/********** INTERFACE iTHEMA ******/
/*************************************/

/*
So wie eine Klasse quasi eine Blaupause für alle später aus ihr zu erstellenden Objekte/Instanzen
darstellt, kann man ein Interface quasi als eine Blaupause für eine später zu erstellende Klasse
ansehen.    Hierzu wird ein Interface definiert, das später in die entsprechene Klasse implementiert
wird. Der Sinn des Interfaces besteht darin, dass innerhalb des Interfaces sämtliche später
innerhalb der Klasse zu erstellende Methoden bereits vordeklariert werden.
Die Klasse muss dann zwingend sämtliche im Interface deklarierten Methoden enthalten.

Ein Interface darf keinerlei Attribute beinhalten.
Die im Interface definierten Methoden müssen public sein und dürfen über keinen
Methodenrumpf {...} verfügen.
An die Methode zu übergebende Parameter müssen im Interface vordefiniert sein ($value).
 */

/*******************************************************************************************/

// Das Schlüsselwort 'abstract' bedeutet, dass aus dieser Klasse keine Instanz
// bzw. kein Objekt gebildet werden kann. Die Klasse kann sich aber nach wie vor
// vererben, so dass stattdessen aus ihren Kinderklassen Instanzen bzw. Objekte gebildet
// werden können.
interface iThema {

    /*
    Ein Interface darf keinerlei Attribute beinhalten.
     */

    /***********************************************************/

    /*********************************/
    /********** KONSTRUKTOR **********/
    /*********************************/

    /***********************************************************/

    /*************************************/
    /********** GETTER & SETTER **********/
    /*************************************/

    /********** Thema_ID **********/
    public function getThema_id();
    public function setThema_id($value);

    /********** THEMA_NAME **********/
    public function getThema_name();
    public function setThema_name($value);

    /***********************************************************/

    /******************************/
    /********** METHODEN **********/
    /******************************/

    /********** CHECK IF THEMA EXISTS IN DB **********/
    public function themaExists($pdo);

    /********** SAVE NEW THEMA TO DB **********/
    public function saveToDb($pdo);

    /********** FETCH ALL THEMES FROM DB  **********/
    //public function fetchFromDb($pdo);
    public static function fetchAllThemesFromDb($pdo);

    /***********************************************************/

    public function fetchThemaFromDb($pdo);

}

/*******************************************************************************************/
