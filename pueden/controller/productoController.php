<?php
require_once '../db/conexion.php'; 
require_once '../modals/productoModel.php';

$model = new ProductoModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $Id = isset($_POST['Id']) ? $_POST['Id'] : null;
        $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : null;
        $Proveedor = isset($_POST['Proveedor']) ? $_POST['Proveedor'] : null;
        $Fecha_de_caducidad = isset($_POST['Fecha_de_caducidad']) ? $_POST['Fecha_de_caducidad'] : null;
        $Stock = isset($_POST['Stock']) ? $_POST['Stock'] : null;
        $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : null;


        if ($action == 'insert') {
            $model->addProducto($Nombre, $Proveedor, $Fecha_de_caducidad, $Stock, $Tipo);
        } elseif ($action == 'update') {
            $model->updateProducto($Id, $Nombre, $Proveedor, $Fecha_de_caducidad, $Stock, $Tipo); // Pasar $id como primer parámetro
        } elseif ($action == 'delete') {
            $model->deleteProducto($Id);
        }        
    }
    header("Location: ../views/empleado/producto.php");
    exit();

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getProducto' && isset($_GET['Id'])) { // Cambiado de 'getEmpleados' a 'getEmpleado'
        $Id = intval($_GET['Id']);
        $producto = $model->getProductoById($Id);

        header('Content-Type: application/json');
        echo json_encode($producto);
        exit();
    }
}

?>
