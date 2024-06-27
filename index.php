<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add publications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<style>
    .bordC {
        border: 1px solid #3c3c3c;
        box-shadow: 5px 5px 5px 5px rgba(42, 241, 252, 0.3);
    }
    .autors-container {
        max-height: 300px;
        overflow-y: scroll;

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
        <div class="card bordC mb-4">
            <div class="card-header text-center">
                <div class="d-flex flex-row justify-content-center">
                    <h2 class="me-4">Add new Publications</h2>
                    <button onclick="redirigir()" class="btn btn-outline-info">Create Word File</button>
                    <button class="btn btn-outline-primary" onclick="document.getElementById('fileInput').click();">Upload RIS File</button>
                </div>
            </div>
            <div class="card-body">
                <form class="needs-validation" id="publicationForm" action="insert.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="COVID-19 vaccination at a hospital in Paris" value="<?php echo isset($autofill_data['title']) ? $autofill_data['title'] : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide a title.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="doi" class="form-label">Doi</label>
                        <input type="text" class="form-control" id="doi" name="doi" required placeholder="bmjgh-2023-012816" value="<?php echo isset($autofill_data['doi']) ? $autofill_data['doi'] : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide a DOI.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="revue" class="form-label">Revue</label>
                        <input type="text" class="form-control" id="revue" name="revue" required placeholder="BMJ GLOBAL HEALTH 2024" value="<?php echo isset($autofill_data['revue']) ? $autofill_data['revue'] : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide a revue.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="lien" class="form-label">URL</label>
                        <input type="text" class="form-control" id="lien" name="lien" required placeholder="www.google.com" value="<?php echo isset($autofill_data['lien']) ? $autofill_data['lien'] : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide a URL.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required value="<?php echo isset($autofill_data['date']) ? date("Y-m-d", strtotime($autofill_data['date'])) : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide a date.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" class="form-control" id="year" name="year" required pattern="\d{4}" placeholder="2024" min="1000" max="9999" oninput="limitInput(this)" value="<?php echo isset($autofill_data['year']) ? $autofill_data['year'] : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide a valid year (4 digits).
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editor" class="form-label">Editor</label>
                        <input type="text" class="form-control" id="editor" name="editor" required placeholder="Enrique Ortiz" value="<?php echo isset($autofill_data['editor']) ? $autofill_data['editor'] : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide an editor.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pages" class="form-label">Pages</label>
                        <input type="text" class="form-control" id="pages" name="pages" required placeholder="23-26" value="<?php echo isset($autofill_data['pages']) ? $autofill_data['pages'] : ''; ?>">
                        <div class="invalid-feedback">
                            Please provide the pages.
                        </div>
                    </div>
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
                        <div class="invalid-feedback">
                            Please select an option
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="keyword" class="form-label">Keyword</label>
                        <input type="text" class="form-control" id="keyword" name="keyword" required placeholder="Accident">
                        <div class="invalid-feedback">
                            Please select a keyword.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Authors</label>
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn btn-outline-success" id="addAuthorBtn">+ Author</button>
                            </div>
                            <div class="card-body autors-container" id="authorsContainer">
                                <?php if (isset($autofill_data['authors'])): ?>
                                    <?php foreach ($autofill_data['authors'] as $index => $author): ?>
                                        <?php
                                            // Dividir el nombre completo del autor en nombre y apellido
                                            $author_parts = explode(', ', $author);
                                            $author_name = isset($author_parts[1]) ? $author_parts[1] : '';
                                            $author_last_name = isset($author_parts[0]) ? $author_parts[0] : '';
                                        ?>
                                        <div class="row mb-2 author-row">
                                            <?php if ($index === 0): ?>
                                                <div class="col-5">
                                                    <label for="author_name" class="form-label">Name</label>
                                                </div>
                                                <div class="col-5">
                                                    <label for="author_last_name" class="form-label">Last Name</label>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-5"></div>
                                                <div class="col-5"></div>
                                            <?php endif; ?>
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="author_name[]" required placeholder="Author Name" value="<?php echo htmlspecialchars($author_name); ?>">
                                            </div>
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="author_last_name[]" required placeholder="Last Name" value="<?php echo htmlspecialchars($author_last_name); ?>">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger removeAuthorBtn">-</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="row mb-2 author-row">
                                        <div class="col-5">
                                            <label for="author_name" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="author_name[]" required placeholder="Author Name">
                                        </div>
                                        <div class="col-5">
                                            <label for="author_last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="author_last_name[]" required placeholder="Last Name">
                                        </div>
                                        <div class="col-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger removeAuthorBtn">-</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                </form>
                <form id="risForm" action="process_ris.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="file" id="fileInput" name="file" accept=".ris" style="display: none;" onchange="document.getElementById('risForm').submit();">
                </form>
            </div>
        </div>
    </div>
    <script>
        function countAuthors() {
            return document.querySelectorAll('.author-row').length;
        }

        function limitInput(input) {
            if (input.value.length > 4) {
                input.value = input.value.slice(0, 4);
            }
        }

        function redirigir() {
            window.location.href = "export.php";
        }
        // JavaScript for adding and removing authors
        document.getElementById('addAuthorBtn').addEventListener('click', function() {
            var authorsContainer = document.getElementById('authorsContainer');
            var newAuthorRow = document.createElement('div');
            newAuthorRow.className = 'row mb-2 author-row';
            newAuthorRow.innerHTML = `
                <div class="col-5">
                    <input type="text" class="form-control" name="author_name[]" required placeholder="Author Name">
                </div>
                <div class="col-5">
                    <input type="text" class="form-control" name="author_last_name[]" required placeholder="Last Name">
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger removeAuthorBtn">-</button>
                </div>
            `;
            authorsContainer.appendChild(newAuthorRow);

            // Add event listener to the new remove button
            newAuthorRow.querySelector('.removeAuthorBtn').addEventListener('click', function() {
                newAuthorRow.remove();
            });
        });

        document.querySelectorAll('.removeAuthorBtn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.author-row').remove();
            });
        });

        // JavaScript for Bootstrap form validation
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

        var publicationForm = document.getElementById('publicationForm');
        publicationForm.addEventListener('submit', function(event) {
            if (countAuthors() === 0) {
                // Mostrar mensaje de error o realizar alguna acción
                alert('Please add at least one author.');
                event.preventDefault(); // Detener el envío del formulario
            }
        });
    </script>
</body>
</html>
