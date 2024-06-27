<?php
include("dbConnect.php");

// Establecer la conexión a UTF-8
$conn->set_charset("utf8mb4");

function generateWordFile($conn, $keyword) {
    $query = "SELECT p.date_p, p.year_p, p.title, p.doi, p.revue, p.lien, p.editeur, p.pages, k.keyword, GROUP_CONCAT(CONCAT(a.name, ' ', a.last_name) SEPARATOR ', ') AS authors 
              FROM publications p 
              JOIN publications_authors pa ON p.id = pa.id_publication 
              JOIN authors a ON a.id = pa.id_author 
              JOIN keywords k ON p.id_keyword = k.id";
    
    if ($keyword != "tout") {
        $query .= " WHERE p.id_keyword = ? GROUP BY p.id ORDER BY year_p DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $keyword);
    } else {
        $query .= " GROUP BY p.id ORDER BY year_p DESC";
        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el contenido del documento
    $content = "<h2>Publications</h2>";
    $current_keyword = '';
    while ($row = $result->fetch_assoc()) {
        if($current_keyword != $row['keyword']) {
            $current_keyword = htmlspecialchars($row['keyword'], ENT_QUOTES, 'UTF-8');
            $content .= "<h3>$current_keyword</h3>";
        }

        $content .= "<p>";
        $content .= htmlspecialchars($row['year_p'], ENT_QUOTES, 'UTF-8') . " ";
        $authors = explode(', ', $row['authors']);
        $formatted_authors = array_map(function($author) {
            list($name, $last_name) = explode(' ', $author);
            $initial = strtoupper(substr($name, 0, 1)) . '.';
            $full_name = "$initial $last_name";
            if ($full_name == 'E. BONNET') {
                return "<b><u>$full_name</u></b>";
            }
            return htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8');
        }, $authors);
        $content .= implode(', ', $formatted_authors) . " ";
        $content .= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . ", ";
        $content .= htmlspecialchars($row['revue'], ENT_QUOTES, 'UTF-8') . ", ";
        $content .= htmlspecialchars($row['pages'], ENT_QUOTES, 'UTF-8') . ", ";
        $content .= htmlspecialchars($row['doi'], ENT_QUOTES, 'UTF-8') . ", ";
        $content .= "</p>";
    }

    $stmt->close();
    
    // Generar y enviar el archivo Word
    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document; charset=UTF-8");
    header("Content-Disposition: attachment; filename=publications.doc");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    echo "<html><head>";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
    echo "</head><body>";
    echo $content;
    echo "</body></html>";

    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = $_POST['keyword'];
    generateWordFile($conn, $keyword);
}
?>
