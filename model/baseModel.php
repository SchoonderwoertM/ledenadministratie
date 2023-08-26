<?php

class BaseModel
{
    //Ontdoet een string van ongewenste slashes en html
    public function sanitizeString($str)
    {
        $str = stripslashes($str);
        $str = strip_tags($str);
        $str = htmlentities($str);
        return $str;
    }

    //Logt gebruiker uit, leegt alle sessie variabelen en beëindigd de sessie.
    public function logout(){
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
        header('Location:index.php');
    }
}