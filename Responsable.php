<?php
include_once "BaseDatos.php";

class Responsable{
    private $rnumeroempleado;
    private $rnumerolicencia;
	private $rnombre;
    private $rapellido;
    private $mensajeoperacion;

    public function __construct(){
        $this->rnumeroempleado = 0;
        $this->rnumerolicencia = "";
        $this->rnombre = "";
        $this->rapellido = "";
    }


    public function setRnumeroempleado($rnumeroempleado){
        $this->rnumeroempleado = $rnumeroempleado;
    }
    public function getRnumeroempleado(){
        return $this->rnumeroempleado;
    }

    public function setRnumerolicencia($rnumerolicencia){
        $this->rnumerolicencia = $rnumerolicencia;
    }
    public function getRnumerolicencia(){
        return $this->rnumerolicencia;
    }

    public function setRnombre($rnombre){
        $this->rnombre = $rnombre;
    }
    public function getRnombre(){
        return $this->rnombre;
    }

    public function setRapellido($rapellido){
        $this->rapellido = $rapellido;
    }
    public function getRapellido(){
        return $this->rapellido;
    }

    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    public function __toString(){
        return "\nNumero de empleado: ".$this->getRnumeroempleado() . "\nNumero de licencia: " 
        . $this->getRnumerolicencia() . "\nNombre: " . $this->getRnombre() . "\nApellido: "
        . $this->getRapellido() . "\n";
    }


    public function cargar($rnumeroempleado, $rnumerolicencia, $rnombre, $rapellido){
        $this->setRnumeroempleado($rnumeroempleado);
        $this->setRnumerolicencia($rnumerolicencia);
        $this->setRnombre($rnombre);
        $this->setRapellido($rapellido);
    }

    /**
	 * Recupera los datos de un responsable por el numero de empleado
	 * @param int $rnumeroempleado
	 * @return boolean
	 */		
    public function Buscar($rnumeroempleado){
		$base=new BaseDatos();
		$consulta="SELECT * FROM responsable WHERE rnumeroempleado=".$rnumeroempleado;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consulta)){
				if($row2=$base->Registro()){
				    $this->setRnumeroempleado($rnumeroempleado);
					$this->setRnumerolicencia($row2['rnumerolicencia']);
					$this->setRnombre($row2['rnombre']);
                    $this->setRapellido($row2['rapellido']);
					$resp= true;
				}
		 	} else {
		 		$this->setmensajeoperacion($base->getError());
			}
		} else {
				$this->setmensajeoperacion($base->getError());
		    }
		return $resp;
	}	

    /**
     * Retorna un arreglo de responsables segun la condicion
     * @param string $condicion
     * @return array
     */
    public static function listar($condicion=""){
	    $arregloResponsable = null;
		$base=new BaseDatos();
		$consultaResponsables="SELECT * from responsable ";
		if ($condicion != ""){
		    $consultaResponsables=$consultaResponsables.' WHERE '.$condicion;
		}
		$consultaResponsables.=" ORDER BY rnumeroempleado ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsables)){				
				$arregloResponsable= array();
				while($row2=$base->Registro()){
				    $rnumeroempleado=$row2['rnumeroempleado'];
					$rnumerolicencia=$row2['rnumerolicencia'];
					$rnombre=$row2['rnombre'];
					$rapellido=$row2['rapellido'];
					$responsable=new Responsable();
					$responsable->cargar($rnumeroempleado,$rnumerolicencia,$rnombre,$rapellido);
                    //$rnumeroempleado, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte
					array_push($arregloResponsable,$responsable);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloResponsable;
	}	

    /**
     * Inserta un responsable en la base de datos, retorna un booleano segun el resultado
     * 
     * @return boolean
     */
    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO responsable(rnumerolicencia, rnombre, rapellido) 
				VALUES ('".$this->getRnumerolicencia().
                "','".$this->getRnombre()."','".
                $this->getRapellido()."')";
		//si da error, revisar los ''
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setRnumeroempleado($id);
			    $resp = true;

			} else {
					$this->setmensajeoperacion($base->getError());
					
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    /**
     * Modifica un responsable, retorna falso o verdadero segun el resultado
     * 
     * @return boolean
     */
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE responsable SET rnumerolicencia='".$this->getRnumerolicencia().
        "',rnombre='".$this->getRnombre()."',rapellido='".
        $this->getRapellido()."' WHERE rnumeroempleado=".$this->getRnumeroempleado();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    /**
     * elimina un responsable, retorna un valor booleano segun el resultado
     * 
     * @return boolean
     */
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM responsable WHERE rnumeroempleado="
                .$this->getRnumeroempleado();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}

}
/*
CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint,
	rnombre varchar(150), 
    rapellido  varchar(150), 
    PRIMARY KEY (rnumeroempleado)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;;
*/