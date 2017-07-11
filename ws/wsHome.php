<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'Sql.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


session_start();

switch ($request->accion) {
    case "consultaGeneral":
        obtenerDatosGenerales($request->fechaInicio, $request->fechaFin);
        break;
    case "consultaGeneralRegion":
        obtenerDatosGeneralesRegion($request->fechaInicio, $request->fechaFin);
        break;
    case "consultaGeneralStatus":
        obtenerDatosStatus($request->fechaInicio, $request->fechaFin);
        break;
    case "consultaGeneralRegionCerrados":
        obtenerDatosGeneralesRegionCerrados($request->status, $request->fechaInicio, $request->fechaFin);
        break;
    
    case "consultaTecnologiaTipo":
        consultaTecnologiaTipo($request->fechaInicio, $request->fechaFin);
        break;
    case "consultaTecnologiaRegion":
        consultaTecnologiaRegion($request->tipo,$request->fechaInicio, $request->fechaFin);
        break;
    case "consultaIdcRegionTipo":
        consultaIdcRegionTipo($request->region, $request->tipo, $request->fechaInicio, $request->fechaFin);
        break;
    case "consultaClientesStatusRegion":
        consultaClientesStatusRegion($request->status, $request->region,$request->fechaInicio, $request->fechaFin);
        break;
    case "consultaStatusRegionTipo":
        consultaStatusRegionTipo($request->region, $request->tipo, $request->fechaInicio, $request->fechaFin);
        break;
    case "consultaTipoSitios":
        consultaTipoSitios($request->fechaInicio, $request->fechaFin);
        break;
    case "consultaRegionSitios":
        consultaRegionSitios($request->tipo, $request->fechaInicio, $request->fechaFin);
        break;

    case "consultaClienteRegion":
        consultaClienteRegion($request->region, $request->fechaInicio, $request->fechaFin);
        break;
    case "cerrarSesion":
        cerrarSesion();
        break;
    default:
        validarSesion();
        break;
}



function consultaClienteRegion($region, $fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        
        $result = $mysqli->query("CALL sp_consultaClienteRegion('$region','$fechaInicio', '$fechaFin')");
        
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $row["status"] = traducirStat($row["status"]);
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function consultaRegionSitios($tipo, $fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        
        $result = $mysqli->query("CALL sp_consultaRegionSitios('$tipo','$fechaInicio', '$fechaFin')");
        
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}



function consultaTipoSitios($fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        
        $result = $mysqli->query("CALL sp_consultaTipoSitios('$fechaInicio', '$fechaFin')");
        
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}



function consultaStatusRegionTipo($region, $tipo, $fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        
        $result = $mysqli->query("CALL sp_consultaStatusRegionTipo('$region','$tipo', '$fechaInicio', '$fechaFin')");
        
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $row["status"] = traducirStat($row["status"]);
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}


function consultaClientesStatusRegion($status, $region,$fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        if ($status == "EJECUCION") {
            $result = $mysqli->query("CALL sp_consultaClientesStatusRegionEjecucion('$region','$fechaInicio', '$fechaFin')");
        } else {
            $status = traducirStatCodigo($status);
            $result = $mysqli->query("CALL sp_consultaClientesStatusRegion('$status','$region', '$fechaInicio', '$fechaFin')");
        }
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function consultaIdcRegionTipo($region, $tipo, $fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        $result = $mysqli->query("CALL sp_consultaIdcRegionTipo('$region','$tipo', '$fechaInicio', '$fechaFin')");
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function consultaTecnologiaTipo($fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        $result = $mysqli->query("CALL sp_consultaTecnologiaTipo('$fechaInicio', '$fechaFin')");
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function consultaTecnologiaRegion($tipo, $fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        $result = $mysqli->query("CALL sp_consultaTecnologiaRegion('$tipo', '$fechaInicio', '$fechaFin')");

        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }

        $mysqli->close();
    }
}

function validarSesion() {
    if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
        echo json_encode($_SESSION['usuario']);
    } else {
        echo '99';
    }
}

function cerrarSesion() {
    if (session_destroy()) {
        echo '1';
    }
}

function obtenerDatosGenerales($fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        $result = $mysqli->query("CALL sp_consultaGeneral('$fechaInicio','$fechaFin')");
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function obtenerDatosGeneralesRegion($fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        $result = $mysqli->query("CALL sp_consultaGeneralRegion('$fechaInicio','$fechaFin')");
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function obtenerDatosStatus($fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);

    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        $result = $mysqli->query("CALL sp_consultaGeneralStatus('$fechaInicio','$fechaFin')");
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $row["status"] = traducirStat($row["status"]);
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function obtenerDatosGeneralesRegionCerrados($status, $fechaInicio, $fechaFin) {
    $db = new Sql();
    $mysqli = new mysqli($db->host, $db->user, $db->password, $db->database);


    if ($mysqli->connect_errno) {
        echo "99";
    } else {
        if ($status == "EJECUCION") {
            $result = $mysqli->query("CALL sp_consultaGeneralRegionCerrados('$fechaInicio','$fechaFin')");
        } else {
            $status = traducirStatCodigo($status);
            $result = $mysqli->query("CALL sp_consultaGeneralRegionStatus('$status','$fechaInicio','$fechaFin')");
        }
        if ($result->num_rows > 0) {
            $json = [];
            while ($row = $result->fetch_assoc()) {
                $json[] = $row;
            }
            echo json_encode($json);
        } else {
            echo '0';
        }
        $mysqli->close();
    }
}

function traducirStat($stat) {
    switch ($stat) {
        case 0:
            $output = "INICIO";
            break;
        case 1:
            $output = "ACEPTADO";
            break;
        case 2:
            $output = "PLANEACION";
            break;
        case 3:
            $output = "EJECUCION";
            break;
        case 4:
            $output = "SEGUIMIENTO Y CONTROL";
            break;

        case 5:
            $output = "EJECUCION";
            break;
        case 6:
            $output = "EJECUCION";
            break;
        case 7:
            $output = "EJECUCION";
            break;
        case 8:
            $output = "EJECUCION";
            break;
        case 200:
            $output = "EJECUCION";
            break;
        case 500:
            $output = "EJECUCION";
            break;
        case 1000:
            $output = "EJECUCION";
            break;
        case 2000:
            $output = "EJECUCION";
            break;
        case 3000:
            $output = "EJECUCION";
            break;
        case 3750:
            $output = "EJECUCION";
            break;

        case 4000:
            $output = "EJECUCION";
            break;

        case 5000:
            $output = "EJECUCION";
            break;

        case 7000:
            $output = "EJECUCION";
            break;

        case 7500:
            $output = "EJECUCION";
            break;

        case 8000:
            $output = "EJECUCION";
            break;

        case 9000:
            $output = "EJECUCION";
            break;

        case 9050:
            $output = "EJECUCION";
            break;

        case 9100:
            $output = "EJECUCION";
            break;

        case 9200:
            $output = "EJECUCION";
            break;

        case 20000:
            $output = "EJECUCION";
            break;

        case 80000:
            $output = "CIERRE";
            break;

        case 90000:
            $output = "CANCELADO";
            break;

        default:
            $output = "UNKNOWN";
    }
    return $output;
}

function traducirStatCodigo($stat) {
    switch ($stat) {
        case "INICIO":
            $output = 0;
            break;
        case "ACEPTADO":
            $output = 1;
            break;
        case "PLANEACION":
            $output = 2;
            break;
        case "EJECUCION":
            $output = 3;
            break;
        case "SEGUIMIENTO Y CONTROL":
            $output = 4;
            break;

        case "Totalidad de routers descargados":
            $output = 5;
            break;
        case "Totalidad de sitios subidos vis XLS":
            $output = 6;
            break;
        case "Memoria Generada":
            $output = 7;
            break;
        case "Memoria Generada y recibida por CARE":
            $output = 8;
            break;
        case "Algunos sitios han sido declarados por SIDC, Aun NO hay Fecha de solicitud para ninguno":
            $output = 200;
            break;
        case "EJECUCION Sitios del proyecto han sido declarados por SIDC":
            $output = 500;
            break;
        case "Algunos sitios han sido declarados por SIDC<br>- Hay fecha de solicitud para algunos sitios":
            $output = 1000;
            break;
        case "Algunos sitios han sido declarados por SIDC Hay fecha de solicitud para algunos sitios Hay IDC asignado para algunos sitios":
            $output = 2000;
            break;
        case "EJECUCION Hay Fecha de confirmacion para algunos sitios":
            $output = 3000;
            break;
        case "Algunos sitios han sido declarados por SIDC Hay fecha de solicitud para algunos sitios Hay IDC asignado para algunos sitios Fecha de confirmacion aceptada por SIDC para algunos sitios":
            $output = 3750;
            break;

        case "Algunos sitios han sido declarados por SIDC, Hay fecha de solicitud para algunos sitios, Hay IDC asignado para algunos sitios, Hay Fecha de confirmacion para algunos sitios, Hay Ingeniero MIP para algunos sitios":
            $output = 4000;
            break;

        case "Memoria tecnica solicitada":
            $output = 5000;
            break;

        case "EJECUCION, Existen errores de generacion de MT":
            $output = 7000;
            break;

        case "EJECUCION, MT generada para algunos sitios":
            $output = "MT generada para algunos sitios ";
            $output = 7500;
            break;

        case "Todos los sitios instalados y entregados a operacion":
            $output = 8000;
            break;

        case "Todos los sitios instalados y entregados a operacion":
            $output = 9000;
            break;

        case "EJECUCION, Algunos sitios entregados a operacion":
            $output = 9050;
            break;

        case "Todos los sitios entregados a operacion":
            $output = 9100;
            break;

        case "Algunos sitios rechazados por operacion":
            $output = 9200;
            break;

        case "Todos los sitios instalados y recibidos por operacion":
            $output = 20000;
            break;

        case "CIERRE":
            $output = 80000;
            break;

        case "CANCELADO":
            $output = 90000;
            break;

        default:
            $output = "UNKNOWN";
    }
    return $output;
}

function getrouterstatusw($status) {
    switch ($status) {
        case 500:
            //$output="- Recien agregado por SIDC<br>- Sin informacion<br>- Sin fecha de instalacion";
            $output = "Informacion Recien agregada por SIDC";
            break;
        case 1:
            $output = "En espera de descarga de datos del router";
            break;
        case 3:
            $output = "NO NO NO Datos del router descargados";
            break;
        case 4:
            $output = "Datos del router descargados";
            break;
        case 5:
            $output = "No se alanza el router por ping, pero ya se tienen los datos";
            break;
        case 6:
            $output = "Memoria Generada";
            break;
        case 7:
            $output = "Orden Mobility Cancelada, faltan datos";
            break;
        case 600:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida";
            break;
        case 700:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado";
        case 701:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado (SIDC de Red Uno)";
            break;
        case 800:
            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada por MIB";
            $output = "- Fecha de Instalacion definida";
            break;
        case 850:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion aprobada por SIDC";
            break;
        case 900:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado";
            break;
        case 1000:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion cancelada Falla Equipo";
            break;
        case 1100:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion cancelada Falla Enlace";
            break;
        case 101100:
            $output = "Instalacion cancelada Falla Enlace";
            break;
        case 1200:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion cancelada Sin acceso";
            break;
        case 1300:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion Reprogramada";
            break;
        case 1400:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion NO Exitosa";
            break;
        case 1420:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Reprogramado Causa: Enlace NO ACTIVADO";
            break;
        case 1440:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Cancelado con IDC en transito";
            break;
        case 1460:
            $output = "- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Cancelado Anticipadamente al servicio";
            break;
        case 1500:
            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion exitosa";
            $output = "Instalacion exitosa";
            break;
        // 1500 Short
        case 101500:
            $output = "Instalacion exitosa";
            break;
        case 1600:
            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion exitosa<br>- MT Solicitada";
            $output = "<li> MT Solicitada";
            break;
        case 1650:
            $output = "<li> Regeneracion de MT Solicitada";
            break;
        case 1700:
            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion exitosa<br>- Error Generando MT";
            $output = "- Error Generando MT";
            break;
        //short
        case 101700:
            $output = "Error Generando MT";
            break;
        case 1800:
            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion exitosa<br>- MT Generada con exito";
            $output = "MT Generada";
            break;

        case 1900:
            $output = "- Pendiente de recepcion por parte de operacion";

            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion exitosa<br>- MT Generada con exito y enviada a Operacion<br>- Pendiente de recepcion por parte de operacion";
            break;

        case 2000:
            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion exitosa<br>- MT Generada con exito y enviada a Operacion<br>- Memoria rechazada por operacion";
            $output = "- Memoria rechazada por operacion";
            break;

        case 2500:
            //$output="- Informacion de acceso capturada<br>- Fecha de Instalacion definida<br>- IDC asignado<br>- Fecha de Instalacion confirmada<br>- MIP Asignado<br>- Instalacion exitosa<br>- MT Generada con exito y enviada a Operacion<br>- Memoria aceptada por operacion";
            $output = "- Memoria aceptada por operacion";
            break;


        default:
            $output = "UNKNOWN ($status)";
    }
    return $output;
}
