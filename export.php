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
        <div class="card bordC">
            <div class="card-header">
                <div class="d-flex flex-row justify-content-center">
                    <h2 class="text-center me-4">Générer un fichier Word</h2>
                    <button type="submit" class="btn btn-outline-info" onclick="goIndex()" >Revenir à la page d'accueil</button>
                </div>
            </div>
            <div class="card-body">
                <h4 class="text-center">Sélectionner les publications à exporter</h4>
                <form method="POST" action="generate.php" class="needs-validation" novalidate id="publicationForm" >
                    <div class="mb-3">
                        <?php 
                        include 'dbConnect.php';
                        $sql = "SELECT * FROM keywords";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0) {
                            echo '<select class="form-select" name="keyword" required>';
                            echo '<option selected disabled value="">Mot-clé</option>';
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . ucfirst($row['keyword']) . '</option>';
                            }
                            echo '<option value="tout">TOUT</option>';
                            echo '</select>';
                        }else {
                            echo '<p>No data</p>';
                        }
                        $conn->close();
                        ?>
                        <div class="invalid-feedback">
                            Veuillez sélectionner un mot-clé.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Envoyer</button>
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