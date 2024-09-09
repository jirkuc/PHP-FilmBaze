<?php
class Films
{
    private $dbConn;

    public function __construct($p_dbConn)
    {
        $this->dbConn = $p_dbConn;
    }

    public function getFilms()
    {
        $sql = "SELECT * FROM films ORDER BY id";
        // stmt = statement
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $films;
    }

    public function getFilm($p_id)
    {
        $sql = "SELECT * FROM films WHERE id = :id";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':id', $p_id, PDO::PARAM_INT);
        $stmt->execute();
        $film = $stmt->fetch(PDO::FETCH_ASSOC);
        return $film;
    }

    public function getNameCz()
    {
        $sql = "SELECT DISTINCT name_cz FROM films ORDER BY name_cz";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $brands;
    }

    public function getNameEn()
    {
        $sql = "SELECT DISTINCT name_en FROM films ORDER BY name_en";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $models;
    }

    public function getReleaseYear()
    {
        $sql = "SELECT DISTINCT release_year FROM films ORDER BY release_year";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        $regs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $regs;
    }

    public function getDirector()
    {
        $sql = "SELECT DISTINCT director FROM films ORDER BY director";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        $regs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $regs;
    }

    public function filterFilms($p_namecz, $p_nameen, $p_relyear, $p_director, $p_starring)
    {
        $sql = "SELECT * FROM films WHERE 1=1";
        $params = [];

        if (!empty($p_namecz) || !$p_namecz == "0") {
            $sql .= " AND name_cz LIKE :namecz";
            $params[':namecz'] = '%' . $p_namecz . '%';
        }
        if (!empty($p_nameen) || !$p_nameen == "0") {
            $sql .= " AND name_en LIKE :nameen";
            $params[':nameen'] = '%' . $p_nameen . '%';
        }
        if (!empty($p_relyear) || !$p_relyear == "0") {
            $sql .= " AND release_year LIKE :relyear";
            $params[':relyear'] = '%' . $p_relyear . '%';
        }
        if (!empty($p_director) || !$p_director == "0") {
            $sql .= " AND director LIKE :director";
            $params[':director'] = '%' . $p_director . '%';
        }
        if (!empty($p_starring) || !$p_starring == "0") {
            $sql .= " AND starring LIKE :starring";
            $params[':starring'] = '%' . $p_starring . '%';
        }

        $stmt = $this->dbConn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $films;
    }

    public function deleteFilm($p_id)
    {
        $sql = "DELETE FROM films WHERE id = :id";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':id', $p_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function addFilm($p_namecz, $p_nameen, $p_relyear, $p_director, $p_starring, $p_filmdesc)
    {
        $sql = "INSERT INTO films (name_cz, name_en, release_year, director, starring, film_desc) VALUES (:namecz, :nameen, :relyear, :director, :starring, :filmdesc)";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':namecz', $p_namecz, PDO::PARAM_STR);
        $stmt->bindParam(':nameen', $p_nameen, PDO::PARAM_STR);
        $stmt->bindParam(':relyear', $p_relyear, PDO::PARAM_INT);
        $stmt->bindParam(':director', $p_director, PDO::PARAM_STR);
        $stmt->bindParam(':starring', $p_starring, PDO::PARAM_INT);
        $stmt->bindParam(':filmdesc', $p_filmdesc, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateFilm($p_id, $p_namecz, $p_nameen, $p_relyear, $p_director, $p_starring, $p_filmdesc)
    {
        $sql = "UPDATE films SET name_cz = :namecz, name_en = :nameen, release_year = :relyear, director = :director, starring = :starring, film_desc = :filmdesc WHERE id = :id";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':id', $p_id, PDO::PARAM_INT);
        $stmt->bindParam(':namecz', $p_namecz, PDO::PARAM_STR);
        $stmt->bindParam(':nameen', $p_nameen, PDO::PARAM_STR);
        $stmt->bindParam(':relyear', $p_relyear, PDO::PARAM_INT);
        $stmt->bindParam(':director', $p_director, PDO::PARAM_STR);
        $stmt->bindParam(':starring', $p_starring, PDO::PARAM_INT);
        $stmt->bindParam(':filmdesc', $p_filmdesc, PDO::PARAM_INT);
        $stmt->execute();
    }
}
