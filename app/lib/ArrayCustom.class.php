<?php
abstract class ArrayCustom {
    
    /* 
    Funcion: Visualiza un Array.
    
    Argumentos: 
                $arr : es un arreglo 
                $tipo : especificamos si es de tipo 'Matriz' o 'Vector'.
    
    Valores Retornados: El metodo no devuelve ningun valor.

    */
    public static function show($arr,$tipo="Matriz"){
        $str = "";
        if ($tipo=="Matriz") {
            foreach ($arr as $item) {
                echo $str = '(';
                foreach ($item as $indice => $valor) {
                    $str.= $valor." ";
                };
                echo $str.") <br>";    
            }
        } else if ($tipo=="Vector") {
            foreach ($arr as $indice => $valor) {
                    $str.= " (".$valor.") ";
                    
            }
            echo $str."<br>";    
        }
    }


}


/*$arreglo = array(array("Materia"=>"informatica","Anio"=>2));
echo ArrayCustom::show(array("Materia"=>"informatica","Anio"=>2),"Vector");*/

