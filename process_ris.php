<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    // Asegúrate de que el archivo tenga la extensión correcta
    $file_info = pathinfo($_FILES['file']['name']);
    if (strtolower($file_info['extension']) == 'ris') {
        // Leer el contenido del archivo
        $ris_data = file_get_contents($file);

        // Procesar el archivo RIS y extraer los datos necesarios
        $autofill_data = processRIS($ris_data);

        // Guardar los datos extraídos en la sesión
        $_SESSION['autofill_data'] = $autofill_data;

        // Redirigir de nuevo al formulario para que los campos se autocompleten
        $_SESSION['message'] = 'RIS file uploaded and data extracted successfully.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Please upload a valid RIS file.';
        $_SESSION['message_type'] = 'danger';
    }
}

header('Location: index.php');
exit;

function processRIS($ris_data) {
    // Función para procesar el archivo RIS y extraer los datos
    $lines = explode("\n", $ris_data);
    $data = [];

    foreach ($lines as $line) {
        if (strpos($line, 'T1  - ') === 0) {
            $data['title'] = substr($line, 6);
        }
        if (strpos($line, 'DO  - ') === 0) {
            $data['doi'] = substr($line, 6);
        }
        if (strpos($line, 'JF  - ') === 0) {
            $data['revue'] = substr($line, 6);
        }
        if (strpos($line, 'UR  - ') === 0) {
            $data['lien'] = substr($line, 6);
        }
        if (strpos($line, 'PY  - ') === 0) {
            $data['year'] = substr($line, 6);
        }
        if (strpos($line, 'Y1  - ') === 0) {
            $data['date'] = substr($line, 6);
        }
        if (strpos($line, 'AU  - ') === 0) {
            $data['authors'][] = substr($line, 6);
        }
        if (strpos($line, 'PB  - ') === 0) {
            $data['editor'] = substr($line, 6);
        }
        if (strpos($line, 'VL  - ') === 0) {
            $data['pages'] = substr($line, 6);
        }
        if (strpos($line, 'KW  - ') === 0) {
            $data['keyword'] = substr($line, 6);
        }
        if (strpos($line, 'TY  - ') === 0) {
            $type = substr($line, 6);
            if ($type == 'JOUR') {
                $data['type'] = 'Article de journal';
            } elseif ($type == 'BOOK') {
                $data['type'] = 'Ouvrages';
            } elseif ($type == 'CHAP') {
                $data['type'] = 'Chapitre';
            }
        }
    }

    return $data;
}
