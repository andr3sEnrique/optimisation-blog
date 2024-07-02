<?php
include("dbConnect.php");
session_start();
// Establecer la conexión a UTF-8
$conn->set_charset("utf8mb4");

function generateWordFile($conn, $keyword) {
    try {
        $query = "SELECT p.date_p, p.year_p, p.title, p.doi, p.revue, p.lien, p.editeur, p.pages, p.type_p, GROUP_CONCAT(CONCAT(a.name, ' ', a.last_name) SEPARATOR ', ') AS authors 
                FROM publications p 
                JOIN publications_authors pa ON p.id = pa.id_publication 
                JOIN authors a ON a.id = pa.id_author";
        
        if ($keyword != "tout") {
            $query .= " WHERE p.type_p = ? GROUP BY p.id ORDER BY year_p DESC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $keyword);
        } else {
            $query .= " GROUP BY p.id ORDER BY year_p DESC";
            $stmt = $conn->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['message'] = 'Aucune publication disponible pour le type sélectionné';
            $_SESSION['message_type'] = 'danger';
            header('Location: export.php');
            exit;
        }
        // Crear el contenido del documento
        $content = "<h2>Publications</h2>";
        $current_keyword = '';
        while ($row = $result->fetch_assoc()) {
            if($current_keyword != $row['type_p']) {
                $current_keyword = htmlspecialchars($row['type_p'], ENT_QUOTES, 'UTF-8');
                if ($current_keyword == "article"){
                    $current_keyword = "$current_keyword de journal";
                }
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
    } catch (Exception $e) {
        // Revertir transacción si hay un error
        $conn->rollback();
        $_SESSION['message'] = 'Ann error was ocurring: ' . $e->getMessage();
        $_SESSION['message_type'] = 'danger';

        // Redirigir de vuelta al formulario en caso de error
        header('Location: export.php');
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = $_POST['type'];
    generateWordFile($conn, $keyword);
}
?>
