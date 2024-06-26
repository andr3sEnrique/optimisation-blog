<?php
session_start();

include("dbConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Obtener el año del campo date
        $date = $_POST['date'];
        $year = $_POST['year'];

        // Verificar el último número de secuencia del año
        $stmt = $conn->prepare("SELECT year_p FROM publications WHERE year_p LIKE ?");
        $like_year = $year . '%';
        $stmt->bind_param("s", $like_year);
        $stmt->execute();
        $stmt->bind_result($year_p);

        $max_number = 0;
        while ($stmt->fetch()) {
            $parts = explode('_', $year_p);
            if (count($parts) == 2 && (int)$parts[1] > $max_number) {
                $max_number = (int)$parts[1];
            }
        }
        $stmt->close();

        $new_year_p = $year . '_' . str_pad($max_number + 1, 2, '0', STR_PAD_LEFT);

        // Insertar publicación
        $stmt = $conn->prepare("INSERT INTO publications (date_p, year_p, title, doi, revue, lien, editeur, pages, keyword) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $date, $new_year_p, $_POST['title'], $_POST['doi'], $_POST['revue'], $_POST['lien'], $_POST['editor'], $_POST['pages'], $_POST['keyword']);
        $stmt->execute();
        $publication_id = $stmt->insert_id;
        $stmt->close();

        // Insertar autores y relacionar con publicación
        $author_names = $_POST['author_name'];
        $author_last_names = $_POST['author_last_name'];

        foreach ($author_names as $index => $name) {
            $author_id = null;
            $last_name = $author_last_names[$index];

            // Verificar si el autor ya existe
            $stmt = $conn->prepare("SELECT id FROM authors WHERE name = ? AND last_name = ?");
            $stmt->bind_param("ss", $name, $last_name);
            $stmt->execute();
            $stmt->bind_result($author_id);
            $stmt->fetch();
            $stmt->close();

            // Si el autor no existe, insertarlo
            if (!$author_id) {
                $stmt = $conn->prepare("INSERT INTO authors (name, last_name) VALUES (?, ?)");
                $stmt->bind_param("ss", strtoupper($name), strtoupper($last_name));
                $stmt->execute();
                $author_id = $stmt->insert_id;
                $stmt->close();
            }

            // Relacionar autor con publicación
            $stmt = $conn->prepare("INSERT INTO publications_authors (id_publications, id_author) VALUES (?, ?)");
            $stmt->bind_param("ii", $publication_id, $author_id);
            $stmt->execute();
            $stmt->close();
        }

        // Confirmar transacción
        $conn->commit();
        $_SESSION['message'] = 'Publication added successfully';
        $_SESSION['message_type'] = 'success';
    } catch (Exception $e) {
        // Revertir transacción
        $conn->rollback();
        $_SESSION['message'] = 'Failed to add publication: ' . $e->getMessage();
        $_SESSION['message_type'] = 'danger';
    }

    $conn->close();
    header('Location: index.php');
    exit();
}
?>
