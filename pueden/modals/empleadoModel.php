<?php
class EmpleadoModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function countEmpleados() {
        $query = "SELECT COUNT(*) AS total FROM empleados";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getEmpleadosPaginated($limit, $offset) {
        $query = "SELECT * FROM empleados LIMIT $limit OFFSET $offset";
        $result = $this->conn->query($query);
        $empleados = [];
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }
        return $empleados;
    }

    public function getEmpleados() {
        $sql = "SELECT * FROM empleados";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addEmpleado($nombre, $apellido, $cargo, $correo, $celular, $estado_civil, $direccion, $fecha_nacimiento, $sueldo) {
        $sql = "INSERT INTO empleados (nombre, apellido, cargo, correo, celular, estado_civil, direccion, fecha_nacimiento, sueldo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
    
        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }
    
        // Vincular parámetros
        $stmt->bind_param("sssssssss", $nombre, $apellido, $cargo, $correo, $celular, $estado_civil, $direccion, $fecha_nacimiento, $sueldo);
    
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
    
    public function updateEmpleado($id, $nombre, $apellido, $cargo, $correo, $celular, $estado_civil, $direccion, $fecha_nacimiento, $sueldo) {
        $sql = "UPDATE empleados SET nombre = ?, apellido = ?, cargo = ?, correo = ?, celular = ?, estado_civil = ?, direccion = ?, fecha_nacimiento = ?, sueldo = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssssi", $nombre, $apellido, $cargo, $correo, $celular, $estado_civil, $direccion, $fecha_nacimiento, $sueldo, $id);
        return $stmt->execute();
    }

    public function deleteEmpleado($id) {
        $sql = "DELETE FROM empleados WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getEmpleadoById($id) {
        $sql = "SELECT * FROM empleados WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Devolver solo un empleado
    }
}
?>
