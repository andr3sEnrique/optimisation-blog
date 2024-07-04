<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Générer un fichier Word</title>
</head>
<style>
    .bordC {
        border: 1px solid #3c3c3c;
        box-shadow: 5px 5px 5px 5px rgba(42, 241, 252, 0.3);
    }
    
</style>
<body>
    
    <div class="container mt-5">
        <?php
            session_start();
            $autofill_data = isset($_SESSION['autofill_data']) ? $_SESSION['autofill_data'] : [];
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">'
                    . $_SESSION['message'] .
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            unset($_SESSION['autofill_data']);
        ?>
        <div class="card bordC">
            <div class="card-header">
                <h2 class="text-center me-4">Générer un fichier Word</h2>
            </div>
            <div class="card-body">
                <h4 class="text-center">Sélectionner les publications à exporter</h4>
                <form method="POST" action="generate.php" class="needs-validation" novalidate id="publicationForm" >
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="article" name="type" id="flexRadioDefault1" <?php echo isset($autofill_data['type']) && $autofill_data['type'] === 'Article de journal' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Article de journal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="ouvrage" name="type" id="flexRadioDefault2" <?php echo isset($autofill_data['type']) && $autofill_data['type'] === 'Ouvrages' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Ouvrages
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="chapitre" name="type" id="flexRadioDefault3" <?php echo isset($autofill_data['type']) && $autofill_data['type'] === 'Chapitre' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="flexRadioDefault3">
                                Chapitre
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="tout" name="type" id="flexRadioDefault3" <?php echo isset($autofill_data['type']) && $autofill_data['type'] === 'Chapitre' ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="flexRadioDefault3">
                                Tout
                            </label>
                        </div>
                        <div class="invalid-feedback">
                            Veuillez sélectionner une option
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-info" onclick="goIndex()" >Revenir à la page d'accueil</button>
                    <button type="submit" class="btn btn-outline-success">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function goIndex () {
            window.location.href = "index.php";
        }
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
