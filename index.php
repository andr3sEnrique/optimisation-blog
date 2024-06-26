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
    
</style>
<body>
    <div class="container mt-5">
        <?php
        session_start();
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">'
                . $_SESSION['message'] .
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>
        <div class="card bordC mb-4">
            <div class="card-header text-center">
                <div class="d-flex flex-row justify-content-center">
                    <h2 class="me-4">Add new Publications</h2>
                    <button onclick="redirigir()" class="btn btn-outline-info">Create Word File</button>
                </div>
            </div>
            <div class="card-body">
                <form class="needs-validation" id="publicationForm" action="insert.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="COVID-19 vaccination at a hospital in Paris">
                        <div class="invalid-feedback">
                            Please provide a title.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="doi" class="form-label">Doi</label>
                        <input type="text" class="form-control" id="doi" name="doi" required placeholder="bmjgh-2023-012816">
                        <div class="invalid-feedback">
                            Please provide a DOI.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="revue" class="form-label">Revue</label>
                        <input type="text" class="form-control" id="revue" name="revue" required placeholder="BMJ GLOBAL HEALTH 2024">
                        <div class="invalid-feedback">
                            Please provide a revue.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="lien" class="form-label">URL</label>
                        <input type="text" class="form-control" id="lien" name="lien" required placeholder="www.google.com">
                        <div class="invalid-feedback">
                            Please provide a URL.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                        <div class="invalid-feedback">
                            Please provide a date.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" class="form-control" id="year" name="year" required pattern="\d{4}" placeholder="2024" min="1000" max="9999" oninput="limitInput(this)">
                        <div class="invalid-feedback">
                            Please provide a valid year (4 digits).
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editor" class="form-label">Editor</label>
                        <input type="text" class="form-control" id="editor" name="editor" required placeholder="Enrique Ortiz">
                        <div class="invalid-feedback">
                            Please provide an editor.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pages" class="form-label">Pages</label>
                        <input type="text" class="form-control" id="pages" name="pages" required placeholder="23-26">
                        <div class="invalid-feedback">
                            Please provide the pages.
                        </div>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="keyword" required>
                            <option selected disabled value="">Keyword</option>
                            <option value="accident">Accident</option>
                            <option value="personal">Personal</option>
                        </select>
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
                            <div class="card-body" id="authorsContainer">
                                <div class="row mb-2 author-row">
                                    <div class="col-5">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="author_name[]" required placeholder="Emmanuel">
                                    </div>
                                    <div class="col-5">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="author_last_name[]" required placeholder="Bonnet">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
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
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="author_name[]" required placeholder="Emmanuel">
                </div>
                <div class="col-5">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="author_last_name[]" required placeholder="Bonnet">

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
    </script>
</body>
</html>
