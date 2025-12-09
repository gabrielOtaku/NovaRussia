<?php
session_start();
$page_title = 'Rendez-vous';
include('../include/config.inc.php');
include('functions.php');

// LOGIQUE DE DÉCONNEXION
if (isset($_GET['deconnexion']) && $_GET['deconnexion'] == 1) {
    session_unset();
    session_destroy();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexion.php");
    exit();
}

$idClient = $_SESSION['idClient'];
$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nom'];
$rv_list = [];
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_rv'])) {
    $rv_a_supprimer = $_POST['rv_a_supprimer'] ?? [];
    if (empty($rv_a_supprimer)) {
        $error = "Veuillez sélectionner au moins un rendez-vous à supprimer.";
    } else {
        try {
            $pdo = connecterBD();
            $pdo->beginTransaction();

            $placeholders = implode(',', array_fill(0, count($rv_a_supprimer), '?'));
            $sql = "DELETE FROM rendezvous WHERE idRV IN ($placeholders) AND noClient = ?";
            $values = array_merge($rv_a_supprimer, [$idClient]);

            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            $pdo->commit();

            header("Location: rendezVous.php?success=supprRV");
            exit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $error = "Erreur de base de données lors de la suppression: " . $e->getMessage();
        }
    }
}

try {
    $pdo = connecterBD();
    $rv_list = obtenirRVClient($pdo, $idClient);

    $image_path = '';
    $exts = ['jpg', 'png', 'jpeg', 'ico'];
    foreach ($exts as $ext) {
        $possible_path = "../Images/{$idClient}.{$ext}";
        if (file_exists($possible_path)) {
            $image_path = $possible_path;
            break;
        }
    }
    if (empty($image_path)) {
        $image_path = "../Images/personnage1.jpg";
    }
} catch (\PDOException $e) {
    $error = "Erreur de base de données lors du chargement des rendez-vous.";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/russia.ico">
    <title>Mes Rendez-vous - Service d'aide NovaRussia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=EB+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/theme.css">

    <style>
        .profile-picture {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--color-secondary);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="text-center">
            <div class="loader"></div>
            <p class="mt-3 text-white custom-font-title animate__animated animate__pulse animate__infinite" style="letter-spacing: 2px;">Chargement NovaRussia</p>
        </div>
    </div>
    <div class="page-content-fadein">
        <?php include('../include/header.inc.php'); ?>

        <main class="container py-5 mt-5">
            <section class="row justify-content-center">
                <div class="col-lg-10 custom-form-container p-5 animate__animated animate__fadeInDown">
                    <h1 class="text-center custom-font-title mb-4 text-primary">Mon Tableau de Bord Guerrier ⚔️</h1>

                    <div class="card text-center mb-5 p-4 bg-light shadow-sm animate__animated animate__fadeInUp">
                        <img src="<?= htmlspecialchars($image_path) ?>" alt="Photo de profil de <?= htmlspecialchars($prenom) ?>" class="profile-picture mx-auto mb-3">
                        <h3 class="custom-font-title text-accent">Bienvenue, <?= htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom) ?>!</h3>
                        <p class="custom-font-body">Votre espace personnel est prêt pour gérer vos missions et rendez-vous.</p>
                        <a href="ajouterRV.php" class="btn btn-royal mt-3 animate__animated animate__tada">
                            <i class="fas fa-calendar-plus"></i> Ajouter un Rendez-vous
                        </a>
                    </div>

                    <?php if ($error): ?>
                        <p class="error-message animate__animated animate__shakeX"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <h2 class="custom-font-title mb-4 text-primary">Mes Rendez-vous Pris</h2>

                    <?php if (empty($rv_list)): ?>
                        <p class="text-center custom-font-body">Vous n'avez aucun rendez-vous planifié pour le moment.</p>
                    <?php else: ?>
                        <form method="POST" action="rendezVous.php">
                            <div class="table-responsive mb-4">
                                <table class="table table-striped table-hover table-royal align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><i class="fas fa-trash-alt"></i></th>
                                            <th>Forfait</th>
                                            <th>Date</th>
                                            <th>Endroit</th>
                                            <th>Remarque</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="custom-font-body">
                                        <?php foreach ($rv_list as $rv): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="rv_a_supprimer[]" value="<?= htmlspecialchars($rv['idRV']) ?>">
                                                </td>
                                                <td class="fw-bold"><?= htmlspecialchars($rv['nomForfait']) ?></td>
                                                <td><?= date('Y-m-d', strtotime($rv['dateRV'])) ?></td>
                                                <td><?= htmlspecialchars($rv['endroit']) ?></td>
                                                <td><?= htmlspecialchars($rv['remarque']) ?></td>
                                                <td>
                                                    <a href="modifier.php?idRV=<?= htmlspecialchars($rv['idRV']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" id="btn-supprimer-rv" name="supprimer_rv" class="btn btn-danger animate__animated animate__rubberBand">
                                    <i class="fas fa-times-circle"></i> Supprimer Rendez-vous
                                </button>
                                <button type="reset" class="btn btn-secondary">Annuler / Décocher</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content custom-form-container animate__animated animate__zoomIn">
                    <div class="modal-header custom-royal-header text-center">
                        <h5 class="modal-title w-100 custom-font-title text-primary" id="modalTitle">Titre</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body custom-font-body text-center" id="modalBody">
                        Message.
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-royal" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include('../include/footer.inc.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
    <script src="../js/monJS.js"></script>
</body>

</html>