<?php
set_include_path('./'.PATH_SEPARATOR.'../conexion'.PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../controller');
require_once('Db.php');

class MantenimientoMaterias
{

    /********************************************************************************************************/
    //Saca las correlativas que debe cumplir una materia. Las correlativas varian si es para Rendir o para Cursar
    // condicion: 'Rendir', 'Cursar'
    //********************************************************************************************************/

    public function eliminaMateriaMultiplesCursados($idAlumno,$idMateria)
    {
        $arr_item = [];
        $arr_resultados = [];

        $query = "DELETE FROM alumno_cursa_materia 
                  WHERE idAlumno=$idAlumno and 
                        idMateria=$idMateria and 
                        anioCursado <> (SELECT max(anioCursado) as anio_cursado FROM alumno_cursa_materia WHERE idAlumno=$idAlumno and idMateria=$idMateria) ";

        //die($query);

        /*

            SELECT max(FechaVencimientoRegularidad) as fecha_vencimiento
            FROM alumno_cursa_materia
            WHERE idAlumno=781 and idMateria=330

            SELECT * 
            FROM alumno_cursa_materia 
            WHERE idAlumno=781 and 
                  idMateria=330 and 
                  anioCursado <> (SELECT max(anioCursado) as anio_cursado FROM alumno_cursa_materia WHERE idAlumno=781 and idMateria=330)

        */
        $result = mysqli_query($this->conn, $query);

        while ($fila = mysqli_fetch_assoc($result)) {
                $arr_item['idAlumno'] = $fila['idAlumno'];
                $arr_item['idMateria'] = $fila['idMateria'];
                $arr_item['cantidad'] = $fila['cantidad'];
                $arr_resultados[] = $arr_item;
        };

        //var_dump($arr_resultados);
        return 'Finalizado';
    }


    public function listarMateriasMultiplesCursados()
    {
        $query = "SELECT idAlumno,idMateria,count(*) as cantidad
                  FROM alumno_cursa_materia 
                  GROUP BY idAlumno,idMateria  
                  HAVING count(*)>1
                  ORDER BY count(*) DESC";
        $result = mysqli_query($this->conn, $query);

        while ($fila = mysqli_fetch_assoc($result)) {
                $this->eliminaMateriaMultiplesCursados($fila['idAlumno'],$fila['idMateria']);
        };

        return 'Finalizado';
    }

}



//$m = new MantenimientoMaterias();
