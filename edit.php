<?php
$title = 'Editace filmu';
include('./DbConnect.php');
require_once('./Films.php');

// vytvoření instance pro připojení do databáze a volání metody, která vytvoří připojení
$connection = new DbConnect();
$database = $connection->connect();

// vytvoření instance Films, jako parametr konstruktoru se předá připojení do databáze
$instanceFilms = new Films($database);
$filmToEdit = [];

// pokud se odeslal formulář s pomocí metody get, spustí se editace
if (isset($_GET['id'])) {
    $filmId = (int) $_GET['id'];
    $filmToEdit = $instanceFilms->getFilm($filmId);
}

// pokud se odeslal formulář s pomocí metody post, spustí se update
if (isset($_POST['update'])) {
    $filmId = (int) $_POST['id'];
    $filmNameCz = htmlspecialchars(trim($_POST['namecz']));
    $filmNameEn = htmlspecialchars(trim($_POST['nameen']));
    $releaseYear = (int) htmlspecialchars(trim($_POST['relyear']));
    $director = htmlspecialchars(trim($_POST['director']));
    $starring = htmlspecialchars(trim($_POST['starring']));
    $filmDesc = htmlspecialchars(trim($_POST['filmdesc']));
    $instanceFilms->updateFilm($filmId, $filmNameCz, $filmNameEn, $releaseYear, $director, $starring, $filmDesc);
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>FilmBáze - <?php echo $title; ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-film"></i> FilmBáze</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Vyhledávání</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">Přidat film</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-3">

        <h2><?php echo $title; ?></h2>

        <?php if ($filmToEdit): ?>
            <form action="edit.php" method="post">
                <input type="hidden" name="id" value="<?php echo $filmToEdit['id']; ?>">
                <div class="row">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="namecz" class="form-label">Český název</label>
                            <input type="text" class="form-control" id="namecz" name="namecz" value="<?php echo $filmToEdit['name_cz']; ?>" required />
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="nameen" class="form-label">Původní název</label>
                            <input type="text" class="form-control" id="nameen" name="nameen" value="<?php echo $filmToEdit['name_en']; ?>" required />
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="relyear" class="form-label">Rok uvedení</label>
                            <input type="numeric" class="form-control" id="relyear" name="relyear" value="<?php echo $filmToEdit['release_year']; ?>" min=1900 max=2100 required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="director" class="form-label">Režisér (Příjmení, Jméno)</label>
                            <input type="text" class="form-control" id="director" name="director" value="<?php echo $filmToEdit['director']; ?>" required />
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="starring" class="form-label">Hrají</label>
                            <input type="text" class="form-control" id="starring" name="starring" value="<?php echo $filmToEdit['starring']; ?>" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="filmdesc" class="form-label">Popis</label>
                            <textarea class="form-control" id="filmdesc" name="filmdesc" cols="60" rows="4" required><?php echo $filmToEdit['film_desc']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Upravit" class="btn btn-primary" name="update" />
                    </div>
                </div>
            </form>
        <?php else: ?>
            <p>Film nelze editovat - nebyl nalezen v databázi.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>