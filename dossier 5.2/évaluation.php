<?php

$dsn = 'mysql:host=localhost;dbname=jo;charset=utf8';
$user = 'root';
$pass = '';

try {
    $dbh = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


function e($s) { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }


$courses = [];
$sth = $dbh->query("SELECT DISTINCT `course` FROM `100` ORDER BY `course` ASC");
while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $courses[] = $row['course'];
}


$messages = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $pays = isset($_POST['pays']) ? trim($_POST['pays']) : '';
    $course = isset($_POST['course']) ? trim($_POST['course']) : '';
    $temps = isset($_POST['temps']) ? trim($_POST['temps']) : '';


    if ($nom === '') {
        $errors[] = "Le nom est requis.";
    }


    $pays = strtoupper($pays);
    if (strlen($pays) !== 3 || !ctype_alpha($pays)) {
        $errors[] = "Le code pays doit comporter exactement 3 lettres (A-Z).";
    }

 
    if ($temps === '' || !is_numeric($temps)) {
        $errors[] = "Le temps doit être un nombre.";
    } else {
 
        $temps = (float) $temps;
        if ($temps < 0) {
            $errors[] = "Le temps doit être positif.";
        }
    }


    if ($course === '' || !in_array($course, $courses, true)) {
        $errors[] = "La course sélectionnée est invalide.";
    }


    if (empty($errors)) {
        $sql = "INSERT INTO `100` (`nom`, `pays`, `course`, `temps`) VALUES (:nom, :pays, :course, :temps)";
        $sth = $dbh->prepare($sql);
        try {
            $sth->execute([
                ':nom' => $nom,
                ':pays' => $pays,
                ':course' => $course,
                ':temps' => $temps
            ]);
            $messages[] = "Résultat ajouté avec succès.";

            if (!in_array($course, $courses, true)) $courses[] = $course;
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'insertion : " . $e->getMessage();
        }
    }
}


$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;


$where = "1=1";
$params = [];
if ($q !== '') {
    $where = "(`nom` LIKE :q OR `pays` LIKE :q OR `course` LIKE :q)";
    $params[':q'] = '%' . $q . '%';
}


$countSql = "SELECT COUNT(*) FROM `100` WHERE $where";
$countSt = $dbh->prepare($countSql);
$countSt->execute($params);
$total = (int) $countSt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $limit));


$dataSql = "SELECT * FROM `100` WHERE $where ORDER BY `course` ASC, `temps` ASC LIMIT :lim OFFSET :off";
$sth = $dbh->prepare($dataSql);


foreach ($params as $k => $v) {
    $sth->bindValue($k, $v, PDO::PARAM_STR);
}
$sth->bindValue(':lim', $limit, PDO::PARAM_INT);
$sth->bindValue(':off', $offset, PDO::PARAM_INT);
$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);


function classementPour(PDO $dbh, $course, $temps) {
    $sql = "SELECT COUNT(*) FROM `100` WHERE `course` = :course AND `temps` < :temps";
    $st = $dbh->prepare($sql);
    $st->execute([':course' => $course, ':temps' => $temps]);
    $c = (int) $st->fetchColumn();
    return $c + 1; 
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>TP - MySQL & Formulaire</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; }
        form { margin-bottom: 20px; background:#f7f7f7; padding:12px; border-radius:6px; }
        label { display:block; margin-top:8px; }
        input[type="text"], input[type="number"], select { padding:6px; width:250px; }
        table { border-collapse: collapse; width:100%; margin-top:10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align:left; }
        th { background: #eee; }
        .msg { color: green; }
        .err { color: red; }
        .pager a { margin: 0 6px; text-decoration: none; }
    </style>
</head>
<body>

<h1>Ajouter un résultat</h1>

<?php foreach ($messages as $m): ?>
    <div class="msg"><?= e($m) ?></div>
<?php endforeach; ?>

<?php foreach ($errors as $err): ?>
    <div class="err"><?= e($err) ?></div>
<?php endforeach; ?>

<form method="post" action="">
    <label>Nom :
        <input type="text" name="nom" required value="<?= isset($_POST['nom']) ? e($_POST['nom']) : '' ?>">
    </label>

    <label>Pays (3 lettres) :
        <input type="text" name="pays" maxlength="3" required value="<?= isset($_POST['pays']) ? e($_POST['pays']) : '' ?>">
    </label>

    <label>Course :
        <select name="course" required>
            <option value="">-- choisir --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= e($c) ?>" <?= (isset($_POST['course']) && $_POST['course'] === $c) ? 'selected' : '' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Temps :
        <input type="text" name="temps" required value="<?= isset($_POST['temps']) ? e($_POST['temps']) : '' ?>">
    </label>

    <div style="margin-top:10px;">
        <button type="submit">Ajouter</button>
    </div>
</form>

<hr>

<h2>Recherche</h2>
<form method="get" action="">
    <input type="text" name="q" placeholder="Rechercher nom / pays / course" value="<?= e($q) ?>">
    <button type="submit">Rechercher</button>
</form>

<p>Résultats : <?= $total ?> — page <?= $page ?> / <?= $totalPages ?></p>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Pays</th>
            <th>Course</th>
            <th>Temps</th>
            <th>Classement (dans la course)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr><td colspan="6">Aucun résultat</td></tr>
    <?php else: ?>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= e($r['nom']) ?></td>
                <td><?= e($r['pays']) ?></td>
                <td><?= e($r['course']) ?></td>
                <td><?= e($r['temps']) ?></td>
                <td><?= classementPour($dbh, $r['course'], $r['temps']) ?></td>
                <td>
                   
                    <?php if (isset($r['id'])): ?>
                        <a href="edit.php?id=<?= (int)$r['id'] ?>">Modifier</a>
                    <?php else: ?>
                        
                        <a href="edit.php?nom=<?= urlencode($r['nom']) ?>&course=<?= urlencode($r['course']) ?>&temps=<?= urlencode($r['temps']) ?>">Modifier</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>


<div class="pager" style="margin-top:12px;">
    <?php

    $baseParams = [];
    if ($q !== '') $baseParams['q'] = $q;
    for ($p = 1; $p <= $totalPages; $p++):
        $baseParams['page'] = $p;
        $qs = http_build_query($baseParams);
    ?>
        <?php if ($p === $page): ?>
            <strong><?= $p ?></strong>
        <?php else: ?>
            <a href="?<?= $qs ?>"><?= $p ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>

</body>
</html>
