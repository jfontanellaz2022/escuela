<?php
require_once('Sanitize.class.php');

abstract class SanitizeCustom extends SanitizeVars {

  
  public static function APELLIDO_NOMBRES($string, $min='2', $max=''){
      $patron = "/^[a-zA-ZñÑ áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ]*$/";
      if (preg_match($patron, $string)) {
      $len = strlen($string);
      if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
      return FALSE;
      return $string;
    } else return FALSE;
  }

  public static function DOCUMENTO_CUIL($string, $min='', $max=''){
    $string = preg_replace("/[^0-9]/", "", $string);
    $len = strlen($string);
    if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max))) return FALSE;
    return $string;
  }

  public static function DOMICILIO($string, $min='2', $max=''){
    $patron = "/^[a-zA-Z0-9ñÑ áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ°.:_-]*$/";
    if (preg_match($patron, $string)) {
      $len = strlen($string);
      if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max))) return FALSE;
      return $string;
    } else return FALSE;
  }

  public static function TOKEN($strNumber){
    $patron = "/^[a-zA-Z0-9_]+$/";
    if (preg_match($patron, $strNumber)) {
      return $strNumber;
    } else return false;
  }


  public static function USUARIOFLEX($strNumber){
    $patron = "/^[a-zA-Z0-9_#$.&@]+$/";
    if (preg_match($patron, $strNumber)) {
      return $strNumber;
    } else return false;
  }

  public static function USUARIO($cadena){
    $patron = '/^[a-zA-Z0-9#_@*\-\$.&]{3,15}$/';

    // Verificar si la cadena cumple con la expresión regular
    if (preg_match($patron, $cadena)) {
        return $cadena;
    } else {
        return false;
    }
  }


 public static function PASSWDFLEX($strNumber){
  $patron = "/^[a-zA-Z0-9#-_*$.&@]+$/"; //#_@*-$.&
  if (preg_match($patron, $strNumber)) {
    return $strNumber;
 } else return false;
}


 public static function PASSWD($cadena){
    // Expresión regular que valida la cadena
    $patron = '/^(?=.*\d)(?=.*[#_@*\-$.&])[A-Za-z\d#_@*\-$.&]{8,10}$/';

    // Validar la cadena con la expresión regular
    if (preg_match($patron, $cadena)) {
        return $cadena; // La cadena es válida
    } else {
        return false; // La cadena no es válida
    }
}


}//-- end CLASS
