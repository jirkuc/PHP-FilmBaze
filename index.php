<?php
$title = 'Přehled filmů';
include('./DbConnect.php');
require_once('./Films.php');

// vytvoření instance pro připojení do databáze a volání metody, která vytvoří připojení
$connection = new DbConnect();
$database = $connection->connect();

// vytvoření instance Films, jako parametr konstruktoru se předá připojení do databáze
$instanceFilms = new Films($database);
$films = $instanceFilms->getFilms();
$cznames = $instanceFilms->getNameCz();
$ennames = $instanceFilms->getNameEn();
$relyears = $instanceFilms->getReleaseYear();
$directors = $instanceFilms->getDirector();

// pokud se odeslal formulář s pomocí metody get a jsou zadány hodnoty v asociativním poli _GET
// odpovídající podmínkám, spustí se filtrování podle podmínky if
if (isset($_GET['namecz']) || isset($_GET['nameen']) || isset($_GET['relyear']) || isset($_GET['director']) || isset($_GET['starring'])) {
    $selectedNameCz = !empty($_GET['namecz']) ? htmlspecialchars(trim($_GET['namecz'])) : '';
    $selectedNameEn = !empty($_GET['nameen']) ? htmlspecialchars(trim($_GET['nameen'])) : '';
    $selectedRelYear = !empty($_GET['relyear']) ? htmlspecialchars(trim($_GET['relyear'])) : '';
    $selectedDirector = !empty($_GET['director']) ? htmlspecialchars(trim($_GET['director'])) : '';
    $selectedStarring = !empty($_GET['starring']) ? htmlspecialchars(trim($_GET['starring'])) : '';
    $selectedFilms = $instanceFilms->filterFilms($selectedNameCz, $selectedNameEn, $selectedRelYear, $selectedDirector, $selectedStarring);
    if ($selectedNameCz == "" && $selectedNameEn == "" && $selectedRelYear == "" && $selectedDirector = "" && $selectedStarring = "") {
        header('Location: index.php');
        exit();
    }
}
// pokud se neodeslal formulář, vypíšou se všechna data z databáze
else {
    $selectedFilms = $films;
}

// mazání filmu
// např (index.php?delete=2)
if (isset($_GET['delete'])) {
    $filmId = (int) $_GET['delete'];
    $instanceFilms->deleteFilm($filmId);
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

        <form action="index.php" method="GET" class="mt-3">
            <div class="row">
                <div class="col-md-5">
                    <label for="namecz" class="form-label">Český název</label>
                    <input type="text" class="form-control" id="namecz" name="namecz" list="filmscz" />
                    <datalist id="filmscz">
                        <?php foreach ($cznames as $namecz) : ?>
                            <option value="<?php echo $namecz['name_cz']; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="col-md-5">
                    <label for="nameen" class="form-label">Původní název</label>
                    <input type="text" class="form-control" id="nameen" name="nameen" list="filmsen" />
                    <datalist id="filmsen">
                        <?php foreach ($ennames as $nameen) : ?>
                            <option value="<?php echo $nameen['name_en']; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="col">
                    <label for="relyear" class="form-label">Rok uvedení</label>
                    <input type="numeric" class="form-control" id="relyear" name="relyear" list="relyears" />
                    <datalist id="relyears">
                        <?php foreach ($relyears as $relyear) : ?>
                            <option value="<?php echo $relyear['release_year']; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="director" class="form-label">Režisér</label>
                    <input type="text" class="form-control" id="director" name="director" list="directors" />
                    <datalist id="directors">
                        <?php foreach ($directors as $director) : ?>
                            <option value="<?php echo $director['director']; ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="col">
                    <label for="starring" class="form-label">Hrají</label>
                    <input type="text" class="form-control" id="starring" name="starring" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <input type="submit" value="Filtrovat" class="btn btn-primary" />
                </div>
            </div>
        </form>

        <table class="table table-sm table-striped table-hover mt-3">
            <thead class="table-primary">
                <tr>
                    <th>Český&nbsp;název</th>
                    <th>Původní název</th>
                    <th>Rok uvedení</th>
                    <th>Režisér</th>
                    <th>Herci</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selectedFilms as $film) : ?>
                    <tr>
                        <th rowspan="2"><?php echo $film['name_cz']; ?></th>
                        <td><?php echo $film['name_en']; ?></td>
                        <td><?php echo $film['release_year']; ?></td>
                        <td><?php echo $film['director']; ?></td>
                        <td><?php echo $film['starring']; ?></td>
                        <td rowspan="2">
                            <!-- <?php echo $film['id']; ?> -->
                            <a href="edit.php?id=<?php echo $film['id']; ?>" class="btn btn-warning m-2"><i class="bi bi-pencil-square"></i></a>
                            <a href="index.php?delete=<?php echo $film['id']; ?>" class="btn btn-danger m-2" onclick="return confirm('Opravdu chcete smazat tento film?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <?php echo $film['film_desc']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>