<?php
require_once '../db/conexion.php'; 
require_once '../modals/empleadoModel.php';

$model = new EmpleadoModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cargo = $_POST['cargo'];
        $correo = $_POST['correo'];
        $celular = $_POST['celular'];
        $estado_civil = $_POST['estado_civil'];
        $direccion = $_POST['direccion'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $sueldo = $_POST['sueldo'];

        if ($action == 'insert') {
            $model->addEmpleado($nombre, $apellido, $cargo, $correo, $celular, $estado_civil, $direccion, $fecha_nacimiento, $sueldo);
        } elseif ($action == 'update') {
            $model->updateEmpleado($id, $nombre, $apellido, $cargo, $correo, $celular, $estado_civil, $direccion, $fecha_nacimiento, $sueldo); // Pasar $id como primer parámetro
        } elseif ($action == 'delete') {
            $model->deleteEmpleado($id);
        }        
    }
    header("Location: ../views/empleado/empleado.php");
    exit();

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getEmpleado' && isset($_GET['id'])) { // Cambiado de 'getEmpleados' a 'getEmpleado'
        $id = intval($_GET['id']);
        $empleado = $model->getEmpleadoById($id);

        header('Content-Type: application/json');
        echo json_encode($empleado);
        exit();
    }
}

?>
