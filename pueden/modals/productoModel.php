<?php
class ProductoModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function countProductos() {
        $query = "SELECT COUNT(*) AS total FROM producto";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getProductosPaginated($limit, $offset) {
        $query = "SELECT * FROM producto LIMIT $limit OFFSET $offset";
        $result = $this->conn->query($query);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }

    public function getProductos() {
        $sql = "SELECT * FROM producto";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addProducto($Nombre, $Proveedor, $Fecha_de_caducidad, $Stock, $Tipo) {
        $sql = "INSERT INTO producto (Nombre, Proveedor, Fecha_de_caducidad, Stock, Tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
    
        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }
    
        // Vincular parámetros
        $stmt->bind_param("sssss", $Nombre, $Proveedor, $Fecha_de_caducidad, $Stock, $Tipo);
    
        // Ejecutar la consulta
        $result = $stmt->execute();
    
        // Verificar si la ejecución fue exitosa
        if($result === true) {
            return "Nuevo registro creado exitosamente";
        } else {
            throw new Exception("Error al crear el registro: " . $stmt->error);
        }
    
        // Cerrar la sentencia preparada
        $stmt->close();
    }
    
    
    public function updateProducto($Id, $Nombre, $Proveedor, $Fecha_de_caducidad, $Stock, $Tipo) {
        $sql = "UPDATE producto SET Nombre = ?, Proveedor = ?, Fecha_de_caducidad = ?, Stock = ?, Tipo = ? WHERE Id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $Nombre, $Proveedor, $Fecha_de_caducidad, $Stock, $Tipo, $Id);
        return $stmt->execute();
    }
    

    public function deleteProducto($Id) {
        $sql = "DELETE FROM producto WHERE Id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $Id);
        return $stmt->execute();
    }

    public function getProductoById($Id) {
        $sql = "SELECT * FROM producto WHERE Id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Devolver solo un empleado
    }
}
?>
