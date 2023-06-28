<?php
include_once "BaseDatos.php";

class Pasajero{
    private $pdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $viaje;
    private $mensajeoperacion;

    public function __construct(){
        $this->pdocumento = 0;
        $this->pnombre = "";
        $this->papellido = "";
        $this->ptelefono = "";
        $this->viaje = "";
    }

    public function setPdocumento($pdocumento){
        $this->pdocumento = $pdocumento;
    }
    public function getPdocumento(){
        return $this->pdocumento;
    }

    public function setPnombre($pnombre){
        $this->pnombre = $pnombre;
    }
    public function getPnombre(){
        return $this->pnombre;
    }

    public function setPapellido($papellido){
        $this->papellido = $papellido;
    }
    public function getPapellido(){
        return $this->papellido;
    }

    public function setPtelefono($ptelefono){
        $this->ptelefono = $ptelefono;
    }
    public function getPtelefono(){
        return $this->ptelefono;
    }

    public function setViaje($viaje){
        $this->viaje = $viaje;
    }
    public function getViaje(){
        return $this->viaje;
    }

    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    public function __toString(){
        return "\nDNI: ".$this->getPdocumento() . "\nNombre: " . $this->getPnombre() . "\nApellido: " .
        $this->getPapellido() . "\nTelefono: " . $this->getPtelefono() . "\n";
    }

    public function cargar($pdocumento, $pnombre, $papellido, $ptelefono, $viaje){
        $this->setPdocumento($pdocumento);
        $this->setPnombre($pnombre);
        $this->setPapellido($papellido);
        $this->setPtelefono($ptelefono);
        $this->setViaje($viaje);
    }


    /**
	 * Recupera los datos de un pasajero por el documento de pasajero
	 * @param int $pdocumento
	 * @return boolean
	 */		
    public function Buscar($pdocumento){
		$base=new BaseDatos();
		$consulta="SELECT * FROM pasajero WHERE pdocumento=".$pdocumento;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consulta)){
				if($row2=$base->Registro()){
                    $viajeBusqueda = new Viaje();
                    $viajeBusqueda->buscar($row2["idviaje"]);
                    
                    $this->cargar($pdocumento, $row2['pnombre'], $row2['papellido'], $row2['ptelefono'], $viajeBusqueda);
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
     * Retorna un arreglo de pasajeros segun la condicion
     * @param string $condicion
     * @return array
     */
    public static function listar($condicion=""){
	    $arregloPasajero = null;
		$base=new BaseDatos();
		$consultaPasajeros="Select * from pasajero ";
		if ($condicion != ""){
		    $consultaPasajeros=$consultaPasajeros.' where '.$condicion;
		}
		$consultaPasajeros.=" order by pdocumento ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajeros)){				
				$arregloPasajero= array();
				while($row2=$base->Registro()){
				    $pdocumento=$row2['pdocumento'];
					$pnombre=$row2['pnombre'];
					$papellido=$row2['papellido'];
					$ptelefono=$row2['ptelefono'];
                    
                    $viaje=new Viaje();
                    $viaje->buscar($row2["idviaje"]);
				
					$pasajero = new pasajero();
					$pasajero->cargar($pdocumento,$pnombre,$papellido,$ptelefono,$viaje);
                    //$idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte
					array_push($arregloPasajero,$pasajero);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloPasajero;
	}	

    /**
     * Inserta un pasajero en la base de datos, retorna un booleano segun el resultado
     * 
     * @return boolean
     */
    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje)
				VALUES ('".$this->getPdocumento()."','".$this->getPnombre().
                "','".$this->getPapellido()."',".
                $this->getPtelefono().",'".
                $this->getViaje()->getIdviaje()."')";
		//si da error, revisar los ''
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){
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
     * Modifica un pasajero, retorna falso o verdadero segun el resultado
     * 
     * @return boolean
     */
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE pasajero SET pnombre='".$this->getPnombre().
        "',papellido='".$this->getPapellido()."',ptelefono='".
        $this->getPtelefono()."',idViaje=". 
        $this->getViaje()->getIdviaje()." WHERE pdocumento=".$this->getPdocumento();
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
     * elimina un pasajero, retorna un valor booleano segun el resultado
     * 
     * @return boolean
     */
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM pasajero WHERE pdocumento="
                .$this->getPdocumento();
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
CREATE TABLE pasajero (
    pdocumento varchar(15),
    pnombre varchar(150), 
    papellido varchar(150), 
	ptelefono int, 
	idviaje bigint,
    PRIMARY KEY (pdocumento),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 
*/