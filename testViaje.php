<?php
include_once "Viaje.php";
include_once "Responsable.php";
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "Pasajero.php";

function menuInicial(){
    $teclado;

    echo "-----------------------------------------------------------\n";
    echo "--------------------Menu principal-------------------------\n";
    echo "-----------------------------------------------------------\n";
    echo "Elija una de las opciones:\n";
    echo "----------------------empresas-----------------------------\n";
    echo "1: Insertar una empresa\n";
    echo "2: Eliminar una empresa\n";
    echo "3: Modificar una empresa\n";
    echo "4: Mostrar la lista de empresas\n";
    echo "-----------------------viajes------------------------------\n";
    echo "5: Insertar un viaje\n";
    echo "6: Eliminar un viaje\n";
    echo "7: Modificar un viaje\n";
    echo "8: Mostrar la lista de viajes\n";
    echo "--------------------Responsables---------------------------\n";
    echo "9: Insertar un responsable\n";
    echo "10: Eliminar un responsable\n";
    echo "11: Modificar un responsable\n";
    echo "12: Mostrar la lista de responsables\n";
    echo "----------------------Pasajeros----------------------------\n";
    echo "13: Insertar un pasajero\n";
    echo "14: Eliminar un pasajero\n";
    echo "15: Modificar un pasajero\n";
    echo "16: Mostrar la lista de pasajeros\n";
    $teclado = trim(fgets(STDIN));
    switch($teclado){
        case 1:
            insertarEmpresa(); //hecho
            break;
        case 2:
            eliminarEmpresa(); //hecho
            break;
        case 3:
            modificarEmpresa(); //hecho
            break;
        case 4:
            listarEmpresas(); //hecho
            break;
        case 5:
            insertarViaje(); //hecho
            break;
        case 6:
            eliminarViaje(); //hecho
            break;
        case 7:
            modificarViaje(); //hecho
            break;
        case 8:
            listarViajes(); //hecho
            break;
        case 9:
            insertarResponsable(); //hecho
            break;
        case 10:
            eliminarResponsable(); //hecho
            break;
        case 11:
            modificarResponsable(); //hecho
            break;
        case 12:
            listarResponsables(); //hecho
            break;
        case 13:
            insertarPasajero(); //hecho
            break;
        case 14:
            eliminarPasajero();
            break;
        case 15:
            modificarPasajero();
            break;
        case 16:
            listarPasajeros();
            break;
        default:
            echo "Debe ingresar un valor valido";
            menuInicial();
            break;
    }
}


///////////////// FUNCIONES ////////////////////////////////////

///////////////// EMPRESA //////////////////////////////////

function insertarEmpresa(){
    $enombre = "";
    $edireccion = "";
    $empresa = new Empresa();
    echo "Ingrese el nombre de la empresa: \n";
    $enombre = trim(fgets(STDIN));
    while(is_numeric($enombre)){
        echo "El nombre no debe ser numerico\n";
        $enombre = trim(fgets(STDIN));
    }
    echo "Ingrese la direccion de la empresa: \n";
    $edireccion = trim(fgets(STDIN));
    while(is_numeric($edireccion)){
        echo "La direccion no debe ser numerica\n";
        $edireccion = trim(fgets(STDIN));
    }

    $empresa->cargar(0, $enombre, $edireccion);
    if($empresa->insertar()){
        echo "La insercion se ha hecho correctamente\n";
        menuInicial();
    } else{
        echo "Ha ocurrido un error: " . $empresa->getmensajeoperacion();
    }

}

function eliminarEmpresa(){
    $idempresa;
    $empresa = new Empresa();
    echo "Ingrese el ID de la empresa a eliminar: \n";
    $idempresa = trim(fgets(STDIN));
    while(!is_numeric($idempresa)){
        echo "el ID debe ser numerico\n";
        $idempresa = trim(fgets(STDIN));
    }
    if($empresa->buscar($idempresa)){
        if($empresa->eliminar()){
            echo "La eliminacion se ha hecho correctamente\n";
            menuInicial();
        } else{
            echo "Ha ocurrido un error al eliminar: " . $empresa->getmensajeoperacion();
        }
    } else{
        echo "Ha ocurrido un error al buscar: " . $empresa->getmensajeoperacion();
    }
}

function modificarEmpresa(){
    $idempresa = "";
    $empresa = new Empresa();
    echo "Ingrese el ID de la empresa a modificar: \n";
    $idempresa = trim(fgets(STDIN));
    while (!is_numeric($idempresa) || $idempresa <= 0){
        echo "el ID debe ser numerico y positivo\n";
        $idempresa = trim(fgets(STDIN));
    }
    if($empresa->buscar($idempresa)){
        $enombre; $edireccion;
        echo "Ingrese el nuevo nombre de la empresa: \n";
        $enombre = trim(fgets(STDIN));
        while(is_numeric($enombre)){
            echo "El nombre no debe ser numerico\n";
            $enombre = trim(fgets(STDIN));
        }
        echo "Ingrese la nueva direccion de la empresa: \n";
        $edireccion = trim(fgets(STDIN));
        while(is_numeric($edireccion)){
            echo "La direccion no debe ser numerica\n";
            $edireccion = trim(fgets(STDIN));
        }
        $empresa->cargar($idempresa, $enombre, $edireccion);
        if($empresa->modificar()){
            echo "La empresa se ha modificado correctamente\n";
            menuInicial();
        } else{
            echo "Ha ocurrido un error al modificar: " . $empresa->getmensajeoperacion();
        }
    } else{
        echo "No existe ninguna empresa con el ID especificado\n";
        menuInicial();
    }
}

function listarEmpresas(){
    $teclado;
    echo "A continuacion especifique una condicion booleana\n";
    echo "Si lo que desea es listar todos los responsables, presione enter\n";
    $teclado = trim(fgets(STDIN));
    $empresa = new Empresa();
    if($listaEmpresas = $empresa->listar($teclado)){
        for($i = 0 ; $i < count($listaEmpresas); $i++){
            //hacer los echos
            echo "\nEmpresa " . $listaEmpresas[$i]->getIdempresa() . ":\n";
            echo $listaEmpresas[$i]->__toString();
        }
        menuInicial();
    } else{
        echo "Se ha producido un error:\n" . $empresa->getmensajeoperacion();
    }
    
}

//////////////////// VIAJES ////////////////////////

function insertarViaje(){
    $idempresa;
    echo "Ingrese el ID de la empresa del viaje: \n";
    $idempresa = trim(fgets(STDIN));
    while(!is_numeric($idempresa) || $idempresa <= 0){
        echo "el ID debe ser numerico y positivo\n";
        $idempresa = trim(fgets(STDIN));
    }
    $empresa = new Empresa();
    if($empresa->buscar($idempresa)){
        $rnumeroempleado;
        echo "Ingrese el numero de responsable del viaje: \n";
        $rnumeroempleado = trim(fgets(STDIN));
        while(!is_numeric($rnumeroempleado) || $rnumeroempleado <= 0){
            echo "el numero de responsable debe ser numerico y positivo\n";
            $rnumeroempleado = trim(fgets(STDIN));
        }
        $responsable = new Responsable();
        if($responsable->buscar($rnumeroempleado)){
            $vdestino; $vcantmaxpasajeros; $vimporte;
            echo "Ingrese el destino del viaje: \n";
            $vdestino = trim(fgets(STDIN));
            while(is_numeric($vdestino)){
                echo "El destino no puede ser numerico\n";
                $vdestino = trim(fgets(STDIN));
            }
            echo "Ingrese la cantidad maxima de pasajeros: \n";
            $vcantmaxpasajeros = trim(fgets(STDIN));
            while(!is_numeric($vcantmaxpasajeros) || $vcantmaxpasajeros <= 0){
                echo "La cantidad de pasajeros debe ser numerica y positiva\n";
                $vcantmaxpasajeros = trim(fgets(STDIN));
            }
            echo "Ingrese el importe del viaje: \n";
            $vimporte = trim(fgets(STDIN));
            while($vimporte <= 0){
                echo "El importe debe ser un numero positivo\n";
                $vimporte = trim(fgets(STDIN));
            }
            $viaje = new Viaje();
            $viaje->cargar(0, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte);
            if($viaje->insertar()){
                echo "La insercion se ha hecho correctamente\n";
                menuInicial();
            } else{
                echo "Ha ocurrido un error: " . $viaje->getmensajeoperacion();
            }
            //$idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte
        } else{
            echo "el numero de responsable ingresado no se encuentra en la base de datos\n";
        }
    } else{
        echo "El ID ingresado no se encuentra en la base de datos\n";
    }
}

function eliminarViaje(){
    $idViaje;
    $viaje = new Viaje();
    echo "Ingrese el ID del viaje a eliminar: \n";
    $idViaje = trim(fgets(STDIN));
    while(!is_numeric($idViaje)){
        echo "el ID debe ser numerico\n";
        $idViaje = trim(fgets(STDIN));
    }
    if($viaje->buscar($idViaje)){
        if($viaje->eliminar()){
            echo "La eliminacion se ha hecho correctamente\n";
            menuInicial();
        } else{
            echo "Ha ocurrido un error al eliminar: " . $viaje->getmensajeoperacion();
        }
    } else{
        echo "Ha ocurrido un error al buscar: " . $viaje->getmensajeoperacion();
    }
}

function modificarViaje(){
    $idviaje;
    echo "Ingrese el ID del viaje a modificar: \n";
    $idviaje = trim(fgets(STDIN));
    while(!is_numeric($idviaje) || $idviaje <= 0){
        echo "el ID debe ser numerico y positivo\n";
        $idviaje = trim(fgets(STDIN));
    }
    $viaje = new Viaje();
    if($viaje->buscar($idviaje)){
        $rnumeroempleado;
        echo "Ingrese el numero del responsable del viaje:\n";
        $rnumeroempleado = trim(fgets(STDIN));
        while(!is_numeric($rnumeroempleado) || $rnumeroempleado <= 0){
            echo "el numero de empleado debe ser numerico y positivo\n";
            $rnumeroempleado = trim(fgets(STDIN));
        }
        $responsable = new Responsable();
        if($responsable->buscar($rnumeroempleado)){
            $idempresa;
            echo "Ingrese el ID de la empresa del viaje:\n";
            $idempresa = trim(fgets(STDIN));
            while(!is_numeric($idempresa) || $idempresa <= 0){
                echo "el ID de la empresa debe ser numerico y positivo\n";
                $idempresa = trim(fgets(STDIN));
            }
            $empresa = new Empresa();
            if($empresa->buscar($idempresa)){
                // $idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte
                $vdestino; $vcantmaxpasajeros; $vimporte;
                echo "Ingrese el nuevo destino del viaje:\n";
                $vdestino = trim(fgets(STDIN));
                while(is_numeric($vdestino)){
                    echo "el destino no debe ser numerico\n";
                    $vdestino = trim(fgets(STDIN));
                }
                echo "Ingrese la cantidad maxima de pasajeros:\n";
                $vcantmaxpasajeros = trim(fgets(STDIN));
                while(!is_numeric($vcantmaxpasajeros) || $vcantmaxpasajeros <= 0){
                    echo "La cantidad debe ser un valor numerico y positivo\n";
                    $vcantmaxpasajeros = trim(fgets(STDIN));
                }
                echo "Ingrese el nuevo importe del viaje:\n";
                $vimporte = trim(fgets(STDIN));
                while(!is_numeric($vcantmaxpasajeros) || $vimporte <= 0){
                    echo "El importe debe ser numerico y positivo\n";
                    $vimporte = trim(fgets(STDIN));
                }
                $viaje->cargar($idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable,
                $vimporte);
                if($viaje->modificar()){
                    echo "El viaje se ha modificado correctamente\n";
                    menuInicial();
                } else{
                    echo "Ha ocurrido un error al modificar: " . $viaje->getmensajeoperacion();
                }
            } else{
                echo "Se ha producido un error al buscar la empresa:\n" . $empresa->getmensajeoperacion();
                menuInicial();
            }
        } else{
            echo "se ha producido un error al buscar al responsable:\n" . $responsable->getmensajeoperacion();
        }
    } else{
        echo "Se ha producido un error al buscar el viaje:\n" . $viaje->getmensajeoperacion();
        menuInicial();
    }
}

function listarViajes(){
    $teclado;
    echo "A continuacion especifique una condicion booleana\n";
    echo "Si lo que desea es listar todos los viajes, presione enter:\n";
    $teclado = trim(fgets(STDIN));
    $viaje = new Viaje();
    if($listaViajes = $viaje->listar($teclado)){
        for($i = 0 ; $i < count($listaViajes); $i++){
            //hacer los echos
            echo "\nViaje " . $listaViajes[$i]->getIdviaje() . ":\n";
            echo $listaViajes[$i]->__toString();
        }
        menuInicial();
    } else{
        echo "Ha ocurrido un error:\n" . $viaje->getmensajeoperacion();
    }
    
}

/////////////////// RESPONSABLES ////////////////////////
function insertarResponsable(){
    $rnumerolicencia; $rnombre; $rapellido;
    echo "Ingrese el numero de licencia: \n";
    $rnumerolicencia = trim(fgets(STDIN));
    while(!is_numeric($rnumerolicencia) || $rnumerolicencia <= 0){
        echo "El numero de licencia debe ser numerico y positivo\n";
        $rnumerolicencia = trim(fgets(STDIN));
    }
    echo "Ingrese el nombre:\n";
    $rnombre = trim(fgets(STDIN));
    while(is_numeric($rnombre)){
        echo "el nombre no debe ser numerico\n";
        $rnombre = trim(fgets(STDIN));
    }
    echo "Ingrese el apellido:\n";
    $rapellido = trim(fgets(STDIN));
    while(is_numeric($rapellido)){
        echo "El apellido no debe ser numerico\n";
        $rapellido = trim(fgets(STDIN));
    }
    $responsable = new Responsable();
    $responsable->cargar(0, $rnumerolicencia, $rnombre, $rapellido);
    if($responsable->insertar()){
        echo "La insercion se ha hecho correctamente\n";
        menuInicial();
    } else{
        echo "ha ocurrido un error:\n" . $responsable->getmensajeoperacion();
    }
}

function eliminarResponsable(){
    $rnumeroempleado;
    echo "Ingrese el numero de empleado que quiera eliminar:\n";
    $rnumeroempleado = trim(fgets(STDIN));
    while(!is_numeric($rnumeroempleado)){
        echo "el valor ingresado debe ser numerico\n";
        $rnumeroempleado = trim(fgets(STDIN));
    }
    $responsable = new Responsable();
    if($responsable->buscar($rnumeroempleado)){
        if($responsable->eliminar()){
            echo "La eliminacion se ha hecho correctamente";
            menuInicial();
        } else{
            echo "Ha ocurrido un error:\n" . $responsable->getmensajeoperacion();
        }
    } else{
        echo "El ID ingresado no se encuentra en la base de datos\n";
        menuInicial();
    }
}

function modificarResponsable(){
    $rnumeroempleado;
    $responsable = new Responsable();
    echo "Ingrese el numero de empleado a modificar: \n";
    $rnumeroempleado = trim(fgets(STDIN));
    while(!is_numeric($rnumeroempleado) || $rnumeroempleado <= 0){
        echo "el numero de empleado debe ser numerico y positivo\n";
        $rnumeroempleado = trim(fgets(STDIN));
    }
    if($responsable->buscar($rnumeroempleado)){
        // $rnumeroempleado, $rnumerolicencia, $rnombre, $rapellido
        $rnumerolicencia; $rnombre; $rapellido;
        echo "Ingrese el nuevo numero de licencia del responsable:\n";
        $rnumerolicencia = trim(fgets(STDIN));
        while(!is_numeric($rnumerolicencia) || $rnumerolicencia <= 0){
            echo "el numero de licencia debe ser numerico y positivo\n";
            $rnumerolicencia = trim(fgets(STDIN));
        }
        echo "Ingrese el nuevo nombre del responsable:\n";
        $rnombre = trim(fgets(STDIN));
        while(is_numeric($rnombre)){
            echo "El nombre no debe ser numerico\n";
            $rnombre = trim(fgets(STDIN));
        }
        echo "Ingrese el nuevo apellido del responsable:\n";
        $rapellido = trim(fgets(STDIN));
        while(is_numeric($rapellido)){
            echo "El apellido no debe ser numerico\n";
            $rapellido = trim(fgets(STDIN));
        }
        $responsable->cargar($rnumeroempleado, $rnumerolicencia, $rnombre,
        $rapellido);
        if($responsable->modificar()){
            echo "El responsable se ha modificado correctamente\n";
            menuInicial();
        } else{
            echo "Ha ocurrido un error al modificar: " . $responsable->getmensajeoperacion();
        }
    } else{
        echo "No existe ningun responsable con el ID especificado\n";
        menuInicial();
    }
}

function listarResponsables(){
    $teclado;
    echo "A continuacion especifique una condicion booleana\n";
    echo "Si lo que desea es listar todos los responsables, presione enter\n";
    $teclado = trim(fgets(STDIN));
    $responsable = new Responsable();
    if($listaResponsables = $responsable->listar($teclado)){
        for($i = 0 ; $i < count($listaResponsables); $i++){
            //hacer los echos
            echo "\nResponsable " . $listaResponsables[$i]->getRnumeroempleado() . ":\n";
            echo $listaResponsables[$i]->__toString();
        }
        menuInicial();
    } else{
        echo "Ha ocurrido un error:\n" . $responsable->getmensajeoperacion();
    }
    
}

/////////////////// PASAJEROS //////////////////////////
function insertarPasajero(){
    $idviaje;
    echo "Ingrese el ID del viaje que quiera comprar:\n";
    $idviaje = trim(fgets(STDIN));
    while(!is_numeric($idviaje) || $idviaje <= 0){
        echo "El ID debe ser numerico y positivo\n";
        $idviaje = trim(fgets(STDIN));
    }
    $viaje = new Viaje();
    if($viaje->buscar($idviaje)){
        $pdocumento; $pnombre; $papellido; $ptelefono;
        echo "Ingrese el numero de documento del pasajero:\n";
        $pdocumento = trim(fgets(STDIN));
        while(!is_numeric($pdocumento) || $pdocumento <= 0){
            echo "El ID debe ser numerico y positivo\n";
            $pdocumento = trim(fgets(STDIN));
        }
        echo "Ingrese el nombre del pasajero:\n";
        $pnombre = trim(fgets(STDIN));
        while(is_numeric($pnombre)){
            echo "El nombre no debe ser numerico\n";
            $pnombre = trim(fgets(STDIN));
        }
        echo "Ingrese el apellido del pasajero:\n";
        $papellido = trim(fgets(STDIN));
        while(is_numeric($papellido)){
            echo "El apellido no debe ser numerico\n";
            $papellido = trim(fgets(STDIN));
        }
        echo "Ingrese el telefono del pasajero:\n";
        $ptelefono = trim(fgets(STDIN));
        while(!is_numeric($ptelefono) || $ptelefono <= 0){
            echo "El telefono debe ser numerico y positivo\n";
            $ptelefono = trim(fgets(STDIN));
        }
        $costePasaje = $viaje->venderPasaje($pdocumento, $pnombre, $papellido, $ptelefono, $viaje);
        if($costePasaje == 0){
            echo "No se ha podido vender el pasaje debido a que no hay mas asientos disponibles\n";
        } else{
            echo "El pasaje se ha vendido correctamente, el costo es de: " . $costePasaje . "\n";
            menuInicial();
        }
    } else{
        echo "Se ha producido un error al buscar el viaje:\n" . $viaje->getmensajeoperacion();
    }
    ;
}

function eliminarPasajero(){
    $pdocumento;
    echo "Ingrese el documento del pasajero que quiera eliminar:\n";
    $pdocumento = trim(fgets(STDIN));
    while(!is_numeric($pdocumento)){
        echo "el valor ingresado debe ser numerico\n";
        $pdocumento = trim(fgets(STDIN));
    }
    $pasajero = new Pasajero();
    if($pasajero->buscar($pdocumento)){
        if($pasajero->eliminar()){
            echo "La eliminacion se ha hecho correctamente\n";
            menuInicial();
        } else{
            echo "Ha ocurrido un error al eliminar:\n" . $pasajero->getmensajeoperacion();
        }
    } else{
        echo "Ha ocurrido un error al buscar el pasajero:\n" . $pasajero->getmensajeoperacion();
        menuInicial();
    }
}

function modificarPasajero(){
    $pdocumento;
    $pasajero = new Pasajero();
    echo "Ingrese el documento del pasajero a modificar: \n";
    $pdocumento = trim(fgets(STDIN));
    while(!is_numeric($pdocumento) || $pdocumento <= 0){
        echo "el documento debe ser numerico y positivo\n";
        $pdocumento = trim(fgets(STDIN));
    }
    if($pasajero->buscar($pdocumento)){
        // $pdocumento, $pnombre, $papellido, $ptelefono
        $pnombre; $papellido; $ptelefono;
        echo "Ingrese el nuevo nombre del pasajero:\n";
        $pnombre = trim(fgets(STDIN));
        while(is_numeric($pnombre)){
            echo "El nombre no debe ser numerico";
            $pnombre = trim(fgets(STDIN));
        }
        echo "Ingrese el nuevo apellido del pasajero:\n";
        $papellido = trim(fgets(STDIN));
        while(is_numeric($papellido)){
            echo "El apellido no debe ser numerico";
            $papellido = trim(fgets(STDIN));
        }
        echo "Ingrese el nuevo telefono del pasajero:\n";
        $ptelefono = trim(fgets(STDIN));
        while(!is_numeric($ptelefono) || $ptelefono <= 0){
            echo "El telefono debe ser numerico y positivo";
            $ptelefono = trim(fgets(STDIN));
        }
        $pasajero->cargar($pdocumento, $pnombre, $papellido,
        $ptelefono, $pasajero->getViaje());
        if($pasajero->modificar()){
            echo "El pasajero se ha modificado correctamente\n";
            menuInicial();
        } else{
            echo "Ha ocurrido un error al modificar: " . $pasajero->getmensajeoperacion();
        }
    } else{
        echo "No existe ningun pasajero con el documento especificado\n";
        menuInicial();
    }
}

function listarPasajeros(){
    $teclado;
    echo "A continuacion especifique una condicion booleana\n";
    echo "Si lo que desea es listar todos los pasajeros, presione enter\n";
    $teclado = trim(fgets(STDIN));
    $pasajero = new Pasajero();
    if($listaPasajeros = $pasajero->listar($teclado)){
        for($i = 0 ; $i < count($listaPasajeros); $i++){
            //hacer los echos
            echo "\nPasajero " . $listaPasajeros[$i]->getPdocumento() . ":\n";
            echo $listaPasajeros[$i]->__toString();
        }
        menuInicial();
    } else{
        echo "Ha ocurrido un error:\n" . $pasajero->getmensajeoperacion();
    }
    
}

menuInicial();