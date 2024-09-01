<?php
/**
 *  Archivo: Session.class.php
 *	Utilidad: Maneja las Sesiones
 *
 * @author Fontanellaz Javier H.
*/
class Session {
    
    /**
     * @param <type> $vars = array de valores a encriptar
     * @return <type> hash usando md5
     */
    public function start(){
        session_start();
    }
    /**
     * @param <type> $hash el hash a controlar
     * @param <type> $vars array de variables a transformar y controlar
     * @return <type> boolean true/false
     */
    public function destroy(){
        session_destroy();
        header("location: http://127.0.0.1/ens40");
    }
}
//-------------------------------- 
?>