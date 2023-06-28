<?php
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "Responsable.php";
include_once "Pasajero.php";

class Viaje{

    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $empresa;
    private $responsable;
    private $vimporte;
    private $mensajeoperacion;

    public function __construct(){
        $this->idviaje = 0;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = "";
        $this->empresa = new Empresa();
        $this->responsable = new Responsable();
        $this->vimporte = "";
    }

    public function setIdviaje($idviaje){
        $this->idviaje = $idviaje;
    }
    public function getIdviaje(){
        return $this->idviaje;
    }

    public function setVdestino($vdestino){
        $this->vdestino = $vdestino;
    }
    public function getVdestino(){
        return $this->vdestino;
    }

    public function setVcantmaxpasajeros($vcantmaxpasajeros){
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }
    public function getVcantmaxpasajeros(){
        return $this->vcantmaxpasajeros;
    }

    public function setEmpresa($empresa){
        $this->empresa = $empresa;
    }
    public function getEmpresa(){
        return $this->empresa;
    }

    public function setResponsable($responsable){
        $this->responsable = $responsable;
    }
    public function getResponsable(){
        return $this->responsable;
    }

    public function setVimporte($vimporte){
        $this->vimporte = $vimporte;
    }
    public function getVimporte(){
        return $this->vimporte;
    }

    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    public function __toString(){
        $pasajero = new Pasajero();
        $arrayPasajeros = $pasajero->listar("idviaje=".$this->getIdviaje());
        $cadenaPasajeros = "";
        for($i = 0 ; $i < count($arrayPasajeros) ; $i++){
            $cadenaPasajeros = $cadenaPasajeros . "\nPasajero " . $i . ":\n"
            . $arrayPasajeros[$i]->__toString(); //poner \n en toString de Pasajero
        }
        return "\nID del viaje: " . $this->getIdviaje() . "\nDestino: " . 
        $this->getVdestino() . "\nCantidad maxima de pasajeros: " . 
        $this->getVcantmaxpasajeros() . "\nID de la empresa: " . 
        $this->getEmpresa()->getIdempresa() . "\nImporte: " . 
        $this->getVimporte() . "\nPasajeros: \n" . $cadenaPasajeros;
    }

    public function cargar($idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte){
        $this->setIdviaje($idviaje);
        $this->setVdestino($vdestino);
        $this->setVcantmaxpasajeros($vcantmaxpasajeros);
        $this->setEmpresa($empresa);
        $this->setResponsable($responsable);
        $this->setVimporte($vimporte);
    }

    /**
	 * Recupera los datos de un viaje por el id de viaje
	 * @param int $idviaje
	 * @return boolean
	 */		
    public function Buscar($idviaje){
		$base=new BaseDatos();
		$consulta="SELECT * FROM viaje WHERE idviaje=".$idviaje;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consulta)){
				if($row2=$base->Registro()){
                    $responsableBusqueda = new Responsable();
                    $responsableBusqueda->Buscar($row2["rnumeroempleado"]);

                    $empresaBusqueda = new Empresa();
                    $empresaBusqueda->buscar($row2["idempresa"]);

                    $this->cargar($idviaje, $row2['vdestino'], $row2['vcantmaxpasajeros'], $empresaBusqueda, 
                    $responsableBusqueda, $row2['vimporte']);
					$resp= true;
                    //$idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte
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
     * Retorna un arreglo de viajes segun la condicion
     * @param string $condicion
     * @return array
     */
    public static function listar($condicion=""){
	    $arregloViaje = null;
		$base=new BaseDatos();
		$consultaViajes="Select * from viaje ";
		if ($condicion != ""){
		    $consultaViajes=$consultaViajes.' where '.$condicion;
		}
		$consultaViajes.=" order by idviaje ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){				
				$arregloViaje= array();
				while($row2=$base->Registro()){
				    $idviaje=$row2['idviaje'];
					$vdestino=$row2['vdestino'];
					$vcantmaxpasajeros=$row2['vcantmaxpasajeros'];
					$vimporte=$row2['vimporte'];
                    
                    $responsable = new Responsable();
                    $responsable->buscar($row2["rnumeroempleado"]);

                    $empresa = new Empresa();
                    $empresa->buscar($row2["idempresa"]);
				
					$viaje=new Viaje();
					$viaje->cargar($idviaje,$vdestino,$vcantmaxpasajeros,$empresa,
                    $responsable, $vimporte);
                    //$idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte
					array_push($arregloViaje,$viaje);
                    //usar buscar
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloViaje;
	}	

    /**
     * Inserta un viaje en la base de datos, retorna un booleano segun el resultado
     * 
     * @return boolean
     */
    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO viaje(vdestino, vcantmaxpasajeros, 
        idempresa, rnumeroempleado, vimporte) 
				VALUES ('".$this->getVdestino()."','".$this->getVcantmaxpasajeros().
                "','".$this->getEmpresa()->getIdempresa()."','".
                $this->getResponsable()->getRnumeroempleado()."','".
                $this->getVimporte()."')";
		//si da error, revisar los ''
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdviaje($id);
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
     * Modifica un viaje, retorna falso o verdadero segun el resultado
     * 
     * @return boolean
     */
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getVdestino().
        "',vcantmaxpasajeros='".$this->getVcantmaxpasajeros()."',idempresa='".
        $this->getEmpresa()->getIdempresa()."',rnumeroempleado=". 
        $this->getResponsable()->getRnumeroempleado().",vimporte=".
        $this->getVimporte()." WHERE idviaje=".$this->getIdviaje();
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
     * elimina un viaje, retorna un valor booleano segun el resultado
     * 
     * @return boolean
     */
    public function eliminar(){
        $base=new BaseDatos();
		$resp=false;
        $pasajero = new Pasajero();
        $colPasajerosDeViaje = $pasajero->listar("idviaje = " . $this->getIdviaje());
        for($i = 0; $i < count($colPasajerosDeViaje); $i++){
            $colPasajerosDeViaje[$i]->eliminar();
        }
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM viaje WHERE idviaje="
                .$this->getIdviaje();
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


    /**
     * Retorna true si hay pasajes disponibles segun el arreglo enviado por parametro
     * false en caso contrario
     * 
     * @param array $arrayPasajeros
     * @return boolean
     */
    public function hayPasajesDisponibles($arrayPasajeros){
        $hayPasajes = false;
        if(count($arrayPasajeros) < $this->getVcantmaxpasajeros()){
            $hayPasajes = true;
        }
        return $hayPasajes;
    }

    /**
     * inserta el pasajero a la colleccion de pasajeros y retorna el costo
     * retorna 0 si no se pudo vender
     * 
     * @param string $pnombre, $papellido
     * @param int $pdocumento, $ptelefono
     * @param Viaje $viaje
     * @return float
     */
    public function venderPasaje($pdocumento, $pnombre, $papellido, 
    $ptelefono, $viaje){
        $costeTotal = 0;
        $pasajero = new Pasajero();
        $condicion = "idviaje=".$viaje->getIdviaje();

        $arrayPasajeros = $pasajero->listar($condicion);
        if($this->hayPasajesDisponibles($arrayPasajeros)){
            $pasajero->cargar($pdocumento, $pnombre, $papellido, $ptelefono, $viaje);
            if($pasajero->insertar()){
                $costeTotal = $this->getVimporte(); //   revisar
            }
        }
        return $costeTotal;
    }
}

/*
CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT,
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
*/