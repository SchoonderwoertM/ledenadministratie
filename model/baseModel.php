<?php
class BaseModel
{
    public function sanitizeString($string)
    {
        $string = htmlentities($string);
        $string = stripslashes($string);
        $string = strip_tags($string);
        $string = trim($string);
        return $string;
    }
}
