<?php
class Database {
    private string $host = "localhost";
    private string $user = "root";
    private string $pass = "";
    private string $dbname = "parcialformulario";

    private ?mysqli $conn = null;

    public function __construct() {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function insertarInscriptor(array $data): bool {
        $sql = "INSERT INTO inscriptores
                (nombre, apellido, edad, sexo, id_pais_residencia,
                 nacionalidad, correo, celular, temas_interes,
                 observaciones, fecha_formulario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssisissssss",
            $data['nombre'],
            $data['apellido'],
            $data['edad'],
            $data['sexo'],
            $data['id_pais_residencia'],
            $data['nacionalidad'],
            $data['correo'],
            $data['celular'],
            $data['temas_interes'],
            $data['observaciones'],
            $data['fecha_formulario']
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function obtenerInscriptos() {
        $sql = "SELECT i.*, p.nombre_pais
                FROM inscriptores i
                INNER JOIN paises p ON i.id_pais_residencia = p.id_pais
                ORDER BY i.fecha_formulario DESC";
        return $this->conn->query($sql);
    }

    public function obtenerPaises() {
        $sql = "SELECT id_pais, nombre_pais FROM paises ORDER BY nombre_pais";
        return $this->conn->query($sql);
    }

    public function obtenerAreas() {
        $sql = "SELECT id_area, nombre_area FROM areas_interes ORDER BY nombre_area";
        return $this->conn->query($sql);
    }


    // Obtener el último ID insertado
    public function getInsertId(): int {
        return $this->conn->insert_id;
    }

    // Obtener la conexión (por ejemplo para usar prepare() fuera)
    public function getConnection(): mysqli {
        return $this->conn;
    }
}
