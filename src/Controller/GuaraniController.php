<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Routing\Annotation\Route;
use App\Tool\HandleParametersTrait;

class GuaraniController extends AbstractController
{
    use HandleParametersTrait;
    private const  borrarProgresar= 'TRUNCATE TABLE int_progresar;';
    private const  ejecutarImportarDatos= 'Select importar_datos_alumnos();';
    private const  registroInfodelosalumnos= 'Select f_int_alumnos_plan_progresar;';
    private const  generarArchivo= 'Select generar_archivos();';
    //private const test= "Select * FROM negocio.sga_propuestas limit 3;";
     private const test= "Select C.codigo FROM negocio.sga_certificados_otorg AS CO
    LEFT JOIN negocio.sga_certificados AS C ON  C.certificado = CO.certificado
    LEFT JOIN negocio.sga_alumnos A ON CO.alumno = A.alumno
    LEFT JOIN negocio.mdp_personas P ON CO.persona = P.persona
    LEFT JOIN negocio.sga_planes_versiones AS PV ON CO.plan_version = PV.plan_version
    LEFT JOIN negocio.sga_planes AS PL ON PV.plan = PL.plan
    LEFT JOIN negocio.sga_propuestas AS Prop ON Prop.propuesta = PL.propuesta
    Where  P.usuario ='42559127' and CO.anulado=0;";
    //pLAN DE ESTUDIO/ PROPUESTAS FORMATIVAS/ ASIGNAR CERTIFICADO
    //private const test= 'Select * FROM INFORMATION_SCHEMA.TABLES LIMIT 3;';
    //private const test= 'Select * FROM negocio.sga_certificados_otorg LIMIT 10;';
    //private const test= "Select  * from information_schema.columns WHERE TABLE_NAME='mdp_datos_estudios' AND COLUMN_NAME='anio_egreso'";


    /*private const test= "Select A.*, Prop.* , Rei.*
    FROM negocio.mdp_personas P LEFT JOIN negocio.sga_alumnos A 
                               ON A.persona = P.persona
                               LEFT JOIN negocio.sga_reinscripciones Rei
                               ON Rei.alumno = A.alumno
                               LEFT JOIN negocio.sga_propuestas Prop 
                               ON A.propuesta = Prop.propuesta
    Where  P.usuario = '41288016' ";*/
    
    /*private const test= "Select P.*, A.regular, Prop.nombre 
                         FROM negocio.mdp_personas P LEFT JOIN negocio.sga_alumnos A 
                                                    ON A.persona = P.persona
                                                    LEFT JOIN negocio.sga_propuestas Prop 
                                                    ON A.propuesta = Prop.propuesta
                         Where P.tipo_usuario_inicial = 'Alumno' AND usuario = '18312706';";*/
    /*private const test= "Select P.*, A.regular 
                         FROM negocio.mdp_personas P LEFT JOIN negocio.sga_alumnos A 
                                                    ON A.persona = P.persona
                                                    LEFT JOIN negocio.sga_propuestas Prop 
                                                    ON P.propuesta = Prop.propuesta
                         Where P.tipo_usuario_inicial = 'Alumno' AND usuario ='18312706';--
                         ";*/
    /*private const query= '"SELECT * FROM negocio.vw_turnos_examen WHERE anio_academico = "' .anio. '";"' . '" SELECT DISTINCT
		 m.llamado_mesa,
		 m.mesa_examen_fecha as fecha,
		 m.turno_examen_nombre,
		 m.llamado_nombre,
		 m.mesa_examen_hora_inicio,
		 ub.nombre as sede,
		 extract(day from m.mesa_examen_fecha) as dia_de_examen,
		 prop.codigo as codigo_carrera,
		 e.codigo as materia_codigo,
		 e.nombre_abreviado as materia_nombre_abreviado,
		 e.nombre as materia_nombre_largo,
		 p.nro_documento as nro_documento,
		 1 as tipo_inscripto
		FROM negocio.vw_mesas_examen as m
		JOIN negocio.sga_ubicaciones as ub ON m.mesa_examen_ubicacion = ub.ubicacion
		JOIN negocio.sga_elementos as e ON e.elemento = m.mesa_examen_elemento
		JOIN negocio.sga_mesas_examen_propuestas as mp ON mp.mesa_examen = m.mesa_examen
		JOIN negocio.sga_propuestas as prop ON prop.propuesta = mp.propuesta
		JOIN negocio.sga_insc_examen as i ON i.llamado_mesa = m.llamado_mesa
		JOIN negocio.sga_alumnos as a ON a.alumno = i.alumno
		JOIN negocio.vw_personas as p ON p.persona = a.persona
		WHERE m.turno_examen = "' . turno .'"
		UNION ALL
		SELECT DISTINCT
		 m.llamado_mesa,
		 m.mesa_examen_fecha as fecha,
		 m.turno_examen_nombre,
		 m.llamado_nombre,
		 m.mesa_examen_hora_inicio,
		 ub.nombre as sede,
		 extract(day from m.mesa_examen_fecha) as dia_de_examen,
		 prop.codigo as codigo_carrera,
		 e.codigo as materia_codigo,
		 e.nombre_abreviado as materia_nombre_abreviado,
		 e.nombre as materia_nombre_largo,
		 p.nro_documento as nro_documento,
		 2 as tipo_inscripto
		FROM negocio.vw_mesas_examen as m
		JOIN negocio.sga_ubicaciones as ub ON m.mesa_examen_ubicacion = ub.ubicacion
		JOIN negocio.sga_elementos as e ON e.elemento = m.mesa_examen_elemento
		JOIN negocio.sga_mesas_examen_propuestas as mp ON mp.mesa_examen = m.mesa_examen
		JOIN negocio.sga_propuestas as prop ON prop.propuesta = mp.propuesta
		JOIN negocio.sga_docentes_mesa_llamado as dll ON dll.llamado_mesa = m.llamado_mesa
		JOIN negocio.sga_docentes as d ON d.docente = dll.docente
		JOIN negocio.vw_personas as p ON p.persona = d.persona
		WHERE m.turno_examen = "' . turno .'"
		ORDER BY fecha, llamado_mesa, tipo_inscripto, nro_documento"'.'";"';*/

    /**
     * 
     * @Route("/", name="inicio")
     */
    public function inicio()
    {
        
        return $this->redirect("/ApiRest/docs");
    }
    public function conexion()
    {
        $conexionGuarani= $this->getParameter('db_guarani');
        return $conexion = pg_connect($conexionGuarani);
    }
    /**
     * 
     * @Route("/datos", name="datos_list", methods="GET")
     */
    public function guarani(): JsonResponse
    {
        try{
            $conexion = $this->conexion();
            $resultado = pg_query($conexion, self::test);
            $result = array();
		    $result = pg_fetch_all($resultado);
            pg_close();
            /*foreach($result as $key => $variable) {
                
                foreach ($variable as $k => $value) {
                    
                    $result[$k]["".$variable]= $this->encriptar($result[$k]["".$variable.""]);
                    return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
                    
                }
            }*/
            return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
        }
    }
    /**
     * 
     * @Route("/borrarProgresar", name="borrar_Progresar", methods="GET")
     */
    public function borrarProgresar(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $RAW_QUERY = $this->borrarProgresar;
        
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return new JsonResponse(['status'=> 'Consult success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
        }
        
    }
    /**
     * 
     * @Route("/ejecutarImportarDatos", name="ejecutar_Importar_Datos", methods="GET")
     */
    public function ejecutarImportarDatos(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $RAW_QUERY = $this->ejecutarImportarDatos;
        
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return new JsonResponse(['status'=> 'Consult success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
        }
        
    }
    /**
     * 
     * @Route("/registroInfodelosalumnos/{anio}", name="registro_Info_delos_alumnos", methods="POST")
     */
    public function registroInfodelosalumnos(Request $request, $anio)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $RAW_QUERY = $this->registroInfodelosalumnos.'('.$anio.')';
        
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return new JsonResponse(['status'=> 'Consult success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
        }
        
    }
     /**
     * 
     * @Route("/isEgresado", name="isEgresado", methods="POST")
     */
    public function isEgresado(Request $request): JsonResponse
    {
        try{
            $dni = null;
            $content = $request->getContent();
            //deserealizar un json
            $arrayjson = json_decode($content,true);
            if (isset($arrayjson["dni"]) and !empty($arrayjson["dni"])) {
                $dni= $arrayjson["dni"];
                $conexion = $this->conexion();
                $resultado = pg_query($conexion, "Select C.codigo FROM negocio.sga_certificados_otorg AS CO
                LEFT JOIN negocio.sga_certificados AS C ON  C.certificado = CO.certificado
                LEFT JOIN negocio.sga_alumnos A ON CO.alumno = A.alumno
                LEFT JOIN negocio.mdp_personas P ON CO.persona = P.persona
                LEFT JOIN negocio.sga_planes_versiones AS PV ON CO.plan_version = PV.plan_version
                LEFT JOIN negocio.sga_planes AS PL ON PV.plan = PL.plan
                LEFT JOIN negocio.sga_propuestas AS Prop ON Prop.codigo = C.codigo
                Where  P.usuario ='$dni' and CO.anulado=0;");
                $result = array();
                $result = pg_fetch_all($resultado);
                pg_close();
                /*foreach($result as $key => $variable) {
                    
                    foreach ($variable as $k => $value) {
                        
                        $result[$k]["".$variable]= $this->encriptar($result[$k]["".$variable.""]);
                        return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
                        
                    }
                }*/
                return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
            }
            return new JsonResponse(['status'=> 'Consult Faild','response'=> 'Faild'], Response::HTTP_CREATED);
           
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'], Response::HTTP_CREATED);
        }
    }
    /**
     * 
     * @Route("/isIncriptoAnioActual", name="isIncriptoAnioActual", methods="POST")
     */
    public function isIncriptoAnioActual(Request $request): JsonResponse
    {
        try{
            $dni = null;
            $year = date("Y");
            $content = $request->getContent();
            //deserealizar un json
            $arrayjson = json_decode($content,true);
            if (isset($arrayjson["dni"]) and !empty($arrayjson["dni"])) {
                $dni= $arrayjson["dni"];
                $conexion = $this->conexion();;
                $resultado = pg_query($conexion, "SELECT		'(' || sga_propuestas.codigo || ') - ' || sga_propuestas.nombre as propuesta_codigo_nombre,
                sga_propuestas.nombre,
                sga_propuestas.codigo,
                sga_propuestas.propuesta,
                sga_reinscripciones.anio_academico,
                sga_reinscripciones.fecha_reinscripcion,
                sga_reinscripciones.nro_transaccion
            FROM		negocio.sga_reinscripciones
                LEFT JOIN negocio.sga_alumnos ON sga_alumnos.alumno = sga_reinscripciones.alumno
                LEFT JOIN negocio.mdp_personas ON mdp_personas.persona = sga_alumnos.persona
                LEFT JOIN negocio.sga_propuestas ON sga_propuestas.propuesta =  sga_alumnos.propuesta
            WHERE		mdp_personas.usuario = '$dni' and
                        sga_reinscripciones.anio_academico = '$year'
            ORDER BY	sga_propuestas.codigo,
                        sga_propuestas.nombre,
                        sga_reinscripciones.anio_academico
            ");
                $result = array();
                $result = pg_fetch_all($resultado);
                pg_close();
                /*foreach($result as $key => $variable) {
                    
                    foreach ($variable as $k => $value) {
                        
                        $result[$k]["".$variable]= $this->encriptar($result[$k]["".$variable.""]);
                        return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
                        
                    }
                }*/
                return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
            }
            return new JsonResponse(['status'=> 'Consult Faild','response'=> 'Faild'], Response::HTTP_CREATED);
           
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'], Response::HTTP_CREATED);
        }
    }
    /**
     * 
     * @Route("/isRegular", name="isRegular", methods="POST")
     */
    public function isRegular(Request $request): JsonResponse
    {
        try{
            $dni = null;
            $content = $request->getContent();
            //deserealizar un json
            $arrayjson = json_decode($content,true);
            if (isset($arrayjson["dni"]) and !empty($arrayjson["dni"])) {
                $dni= $arrayjson["dni"];
                $conexion = $this->conexion();
                $resultado = pg_query($conexion, "Select A.regular, Prop.* 
                FROM negocio.mdp_personas P LEFT JOIN negocio.sga_alumnos A 
                                           ON A.persona = P.persona
                                           LEFT JOIN negocio.sga_propuestas Prop 
                                           ON A.propuesta = Prop.propuesta
                Where  P.usuario ='$dni' and  A.regular='S';");
                $result = array();
                $result = pg_fetch_all($resultado);
                pg_close();
                /*foreach($result as $key => $variable) {
                    
                    foreach ($variable as $k => $value) {
                        
                        $result[$k]["".$variable]= $this->encriptar($result[$k]["".$variable.""]);
                        return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
                        
                    }
                }*/
                return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
            }
            return new JsonResponse(['status'=> 'Consult Faild','response'=> 'Faild'], Response::HTTP_CREATED);
           
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'], Response::HTTP_CREATED);
        }
    }

    /**
     * 
     * @Route("/iscondicionAlumno", name="iscondicionAlumno", methods="POST")
     */
    public function iscondicionAlumno(Request $request): JsonResponse
    {
        try{
            $isRegular= $this->isRegular($request)->getContent();
            $isRegular= json_decode($isRegular,true);
            $arrayMatRegulares= array();
            //buscamos la lista de materias en las que esta regular
            if ($isRegular["status"]== 'Consult success' and $isRegular["response"]!= false){
                foreach ($isRegular["response"] as $value) {
                    //guardo las materias que son regulares en un array
                    if($value['regular']="S"){
                        array_push($arrayMatRegulares, $value['codigo']);
                    }
                }
            }else{
                return new JsonResponse(['status'=> 'Consult success','response'=>'NO', 'error'=>'No tiene materias regulares'], Response::HTTP_CREATED);
            }
            //identificar si es egresado en alguna de esas materias
            $isEgresado= $this->isEgresado($request)->getContent();
            $isEgresado= json_decode($isEgresado,true);
            if ($isEgresado["status"]== 'Consult success' and $isEgresado["response"]!= false){
                foreach ($isEgresado["response"] as $value) {
                    if(($clave = array_search($value['codigo'], $arrayMatRegulares))!== false){
                        unset($arrayMatRegulares[$clave]);
                    }
                }
            }
            //si el array esta vacio es porque no tiene otras materias y las que tenia esta egresado
            if (count($arrayMatRegulares)==0) {
                return new JsonResponse(['status'=> 'Consult success','response'=>'NO', 'error'=>'El Alumno esta egresado'], Response::HTTP_CREATED);
            }
            //Determinar si esta inscripto en el año actual
            $isIncriptoAnioActual= $this->isIncriptoAnioActual($request)->getContent();
            $isIncriptoAnioActual= json_decode($isIncriptoAnioActual,true);
            if ($isIncriptoAnioActual["status"]== 'Consult success' and $isIncriptoAnioActual["response"]!= false){
                foreach ($isIncriptoAnioActual["response"] as $value) {
                    if(($clave = array_search($value['codigo'], $arrayMatRegulares))!== false){
                        return new JsonResponse(['status'=> 'Consult success','response'=>'SI', 'error'=>false], Response::HTTP_CREATED);
                    }
                }
            }
            //Si esta regular en una materia o esta egresado en una y regular en otra pero en la seguda
            //no esta inscripto en el año acual
            return new JsonResponse(['status'=> 'Consult success','response'=>'NO', 'error'=>'No esta inscripto'], Response::HTTP_CREATED);

           // return new JsonResponse(['status'=> 'Consult Faild','response'=> 'Faild'], Response::HTTP_CREATED);
           
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
        }
    }
     /**
     * 
     * @Route("/listandoMesas", name="listandoMesas", methods="POST")
     */
    public function listandoMesas(): JsonResponse
    {
        try{
            $anio_academico = null;
            $anio_academico= $data["dni"];
            $conexion = $this->conexion();
            $resultado = pg_query($conexion, "Select P.*, A.regular, Prop.nombre 
            FROM negocio.mdp_personas P LEFT JOIN negocio.sga_alumnos A 
                                       ON A.persona = P.persona
                                       LEFT JOIN negocio.sga_propuestas Prop 
                                       ON A.propuesta = Prop.propuesta
            Where P.tipo_usuario_inicial = 'Alumno' AND usuario ='$anio_academico';");
            $result = array();
		    $result = pg_fetch_all($resultado);
            pg_close();
            /*foreach($result as $key => $variable) {
                
                foreach ($variable as $k => $value) {
                    
                    $result[$k]["".$variable]= $this->encriptar($result[$k]["".$variable.""]);
                    return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
                    
                }
            }*/
            return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
        }
    }
    /**
     * 
     * @Route("/listandoMesas", name="listandoMesas", methods="POST")
     */
    /*public function listandoMesas(Request $request)
    {

        try {
            $content = $request->getContent();
            $data = json_decode($content, true);
            $anio_academico = null;
            $turno_academico = null;
            $anio_academico=$data["anio_academico"];
            $turno_academico=$data["turno_academico"];
            /*$content = $_POST["content"];
            $data = json_decode($jsonString);
            echo $anio_academico->response;
            echo $turno_academico->turno_academico;*/
            //$result= $this->desencriptar("2+ZEUy0SKxCBn897/WxYqXYSZVESkgtFbF8JTGLOvY0=");

            //$result= "BtZwQKi1rbwviv/L+/VuSg==";
            
            //$result= $this->desencriptar($anio_academico); 
           // return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
       // } catch (\Throwable $th) {
        //    return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
       // }
   // }
    /**
     * 
     * @Route("/generarArchivo", name="generar_Archivo", methods="POST")
     */
    public function generarArchivo(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $RAW_QUERY = $this->generarArchivo;
        
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return new JsonResponse(['status'=> 'Consult success','response'=> $result], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return new JsonResponse(['status'=> 'Consult Error'.$th], Response::HTTP_CREATED);
        }
        
    }

}
    
