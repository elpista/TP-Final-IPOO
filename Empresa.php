<?php
include_once "BaseDatos.php";

class Empresa {
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion;

    public function __construct(){
        $this->idempresa = 0;
        $this->enombre = "";
        $this->edireccion = "";
    }


    public function setIdempresa($idempresa){
        $this->idempresa = $idempresa;
    }
    public function getIdempresa(){
        return $this->idempresa;
    }

    public function setEnombre($enombre){
        $this->enombre = $enombre;
    }
    public function getEnombre(){
        return $this->enombre;
    }

    public function setEdireccion($edireccion){
        $this->edireccion = $edireccion;
    }
    public function getEdireccion(){
        return $this->edireccion;
    }

    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    public function __toString(){
        return "\n Nombre de la empresa: " . $this->getEnombre() . "\n Direccion de la empresa: " 
        . $this->getEdireccion() . "\n";
    }

    public function cargar($idempresa, $enombre, $edireccion){
        $this->setIdempresa($idempresa);
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }

    /**
	 * Recupera los datos de una empresa por el id de empresa
	 * @param int $idempresa
	 * @return boolean
	 */		
    public function Buscar($idempresa){
		$base=new BaseDatos();
		$consulta="SELECT * FROM empresa WHERE idempresa=".$idempresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consulta)){
				if($row2=$base->Registro()){
				    $this->setIdempresa($idempresa);
					$this->setEnombre($row2['enombre']);
					$this->setEdireccion($row2['edireccion']);
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
     * Retorna un arreglo de empresas segun la condicion
     * @param string $condicion
     * @return array
     */
    public static function listar($condicion=""){
	    $arregloEmpresa = null;
		$base=new BaseDatos();
		$consultaEmpresas="Select * from empresa ";
		if ($condicion != ""){
		    $consultaEmpresas=$consultaEmpresas.' where '.$condicion;
		}
		$consultaEmpresas.=" order by idempresa ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresas)){				
				$arregloEmpresa= array();
				while($row2=$base->Registro()){
				    $idempresa=$row2['idempresa'];
					$enombre=$row2['enombre'];
					$edireccion=$row2['edireccion'];
					$empresa=new Empresa();
					$empresa->cargar($idempresa,$enombre,$edireccion);
					array_push($arregloEmpresa,$empresa);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloEmpresa;
	}	

    /**
     * Inserta una empresa en la base de datos, retorna un booleano segun el resultado
     * 
     * @return boolean
     */
    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(enombre, edireccion) 
				VALUES ('".$this->getEnombre()."','".
                $this->getEdireccion()."')";
		//si da error, revisar los ''
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdempresa($id);
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
     * Modifica una empresa, retorna falso o verdadero segun el resultado
     * 
     * @return boolean
     */
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE empresa SET enombre='".$this->getEnombre().
        "',edireccion='".$this->getEdireccion()."' WHERE idempresa=".$this->getIdempresa();
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
     * elimina una empresa, retorna un valor booleano segun el resultado
     * 
     * @return boolean
     */
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		$viaje = new Viaje();
        $colViajesDeEmpresa = $viaje->listar("idempresa = " . $this->getIdempresa());
        for($i = 0; $i < count($colViajesDeEmpresa); $i++){
            $colViajesDeEmpresa[$i]->eliminar();
        }
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM empresa WHERE idempresa="
                .$this->getIdempresa();
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
CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
*/