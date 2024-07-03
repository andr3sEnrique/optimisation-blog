<?php
header('Content-Type: application/json');

include("dbConnect.php");
$conn->set_charset("utf8mb4");

// Obtener el parámetro mot-cle de la solicitud
$motCle = isset($_GET['mot-cle']) ? $_GET['mot-cle'] : '';

if (empty($motCle)) {
    echo json_encode(["error" => "Le paramètre keyword est requis."]);
    exit();
}

// Consulta SQL
$sql = "SELECT p.date_p, p.year_p, p.title, p.doi, p.revue, p.lien, p.editeur, p.pages, p.type_p, GROUP_CONCAT(CONCAT(a.name, ' ', a.last_name) SEPARATOR ', ') AS authors 
        FROM publications p 
        JOIN publications_authors pa ON p.id = pa.id_publication 
        JOIN keywords k ON p.id_keyword = k.id
        JOIN authors a ON a.id = pa.id_author 
        WHERE k.keyword = ?
        GROUP BY p.id";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["error" => "Erreur dans la préparation de la requete : " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $motCle);
$stmt->execute();
$result = $stmt->get_result();

$data = array();
if ($result->num_rows > 0) {
    // Salida de datos de cada fila
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = [];
}

$stmt->close();
$conn->close();

echo json_encode($data);
?>
