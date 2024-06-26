<?php
include("dbConnect.php");

function generateWordFile($conn, $keyword) {
    $query = "SELECT p.date_p, p.year_p, p.title, p.doi, p.revue, p.lien, p.editeur, p.pages, p.keyword, GROUP_CONCAT(CONCAT(a.name, ' ', a.last_name) SEPARATOR ', ') AS authors FROM publications p JOIN publications_authors pa ON p.id = pa.id_publications JOIN authors a ON a.id = pa.id_author";
    
    if ($keyword != "both") {
        $query .= " WHERE keyword = ? GROUP BY p.id ORDER BY year_p DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $keyword);
    } else {
        $query .= " GROUP BY p.id ORDER BY year_p DESC";
        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el contenido del documento
    $content = "<h1>Publications</h1>";
    
    while ($row = $result->fetch_assoc()) {
        $content .= "<p>";
        $content .= $row['year_p'] . " ";
        $content .= $row['authors'] . " ";
        $content .= $row['title'] . ", ";
        $content .= $row['revue'] . ", ";
        $content .= $row['pages'] . ", ";
        $content .= $row['doi'] . ", ";
        $content .= '<a href="' . $row['lien'] . '">' . $row['lien'] . '</a>';
        $content .= "</p>";
    }

    $stmt->close();
    
    // Generar y enviar el archivo Word
    header("Content-Type: application/msword");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Disposition: attachment; filename=publications.doc");
    echo "<html><body>";
    echo $content;
    echo "</body></html>";

    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = $_POST['keyword'];
    generateWordFile($conn, $keyword);
}
?>
