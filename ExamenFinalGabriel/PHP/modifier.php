<?php
session_start();
$page_title = 'Modifier un Rendez-vous';
require_once('../include/config.inc.php');
include('functions.php');


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexion.php");
    exit();
}

$idClient = $_SESSION['idClient'];
$error = '';
$rv_data = null;
$idRV = $_GET['idRV'] ?? $_POST['idRV'] ?? null;
$min_date = date('Y-m-d', strtotime('+2 days'));

$noForfait = '';
$dateRV = '';
$endroit = '';
$remarque = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noForfait = trim($_POST['noForfait'] ?? '');
    $dateRV = trim($_POST['dateRV'] ?? '');
    $endroit = trim($_POST['endroit'] ?? '');
    $remarque = trim($_POST['remarque'] ?? '');

    try {
        $pdo = connecterBD();
        $rv_data = obtenirRVParId($pdo, $idRV, $idClient);
    } catch (\PDOException $e) {
        $error = "Erreur de base de données initiale.";
    }

    if (empty($noForfait) || empty($dateRV)) {
        $error = 'Veuillez sélectionner un forfait et une date.';
    } elseif (!strtotime($dateRV)) {
        $error = 'Format de date invalide.';
    } elseif ($dateRV < $min_date) {
        $error = "La date du rendez-vous doit être au moins 2 jours après aujourd'hui. Date minimale: " . date('Y-m-d', strtotime($min_date));
    } elseif ($rv_data === false) {
        $error = "Rendez-vous introuvable ou vous n'avez pas l'autorisation de le modifier.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE rendezvous SET noForfait = ?, dateRV = ?, endroit = ?, remarque = ? WHERE idRV = ? AND noClient = ?");
            $stmt->execute([$noForfait, $dateRV, $endroit, $remarque, $idRV, $idClient]);

            header("Location: rendezvous.php?success=modifRV");
            exit();
        } catch (\PDOException $e) {
            $error = "Erreur de base de données lors de la modification du rendez-vous.";
        }
    }
} else {
    if (empty($idRV)) {
        header("Location: rendezvous.php");
        exit();
    }
    try {
        $pdo = connecterBD();
        $rv_data = obtenirRVParId($pdo, $idRV, $idClient);

        if ($rv_data === false) {
            $error = "Rendez-vous introuvable ou vous n'avez pas l'autorisation de le modifier.";
        }

        $noForfait = $rv_data['noForfait'] ?? '';
        $dateRV = $rv_data['dateRV'] ?? '';
        $endroit = $rv_data['endroit'] ?? '';
        $remarque = $rv_data['remarque'] ?? '';
    } catch (\PDOException $e) {
        $error = "Erreur de base de données lors du chargement initial.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/russia.ico">
    <title>Modifier un Rendez-vous - Service d'aide NovaRussia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=EB+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/theme.css">
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
                <div class="col-lg-6 custom-form-container p-5 animate__animated animate__zoomIn">
                    <h2 class="text-center custom-font-title mb-4 text-primary">Modifier un Rendez-vous #<?= htmlspecialchars($idRV) ?> 📝</h2>

                    <?php if (isset($rv_data) && $rv_data === false): ?>
                        <p class="error-message text-center"><i class="fas fa-exclamation-triangle"></i> Rendez-vous introuvable ou vous n'avez pas l'autorisation de le modifier.</p>
                        <div class="text-center mt-4"><a href="rendezVous.php" class="btn btn-secondary">Retour aux Rendez-vous</a></div>
                    <?php else: ?>
                        <form method="POST" action="modifier.php" class="mt-4 form-modifier-rv">

                            <?php if ($error): ?>
                                <p class="error-message animate__animated animate__shakeX"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></p>
                            <?php endif; ?>

                            <input type="hidden" name="idRV" value="<?= htmlspecialchars($idRV) ?>">

                            <div class="mb-3">
                                <label for="forfait" class="form-label custom-font-body">Forfait:</label>
                                <?php
                                try {
                                    $pdo = $pdo ?? connecterBD();
                                    echo genererListeForfaits($pdo, $noForfait);
                                } catch (\PDOException $e) {
                                    echo '<p class="text-danger">Erreur de connexion pour les forfaits.</p>';
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label for="dateRV" class="form-label custom-font-body">Date:</label>
                                <input type="date" class="form-control custom-royal-input" id="dateRV" name="dateRV"
                                    required min="<?= htmlspecialchars($min_date) ?>" value="<?= htmlspecialchars(date('Y-m-d', strtotime($dateRV))) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="endroit" class="form-label custom-font-body">Endroit:</label>
                                <input type="text" class="form-control custom-royal-input" id="endroit" name="endroit" maxlength="50" value="<?= htmlspecialchars($endroit) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="remarque" class="form-label custom-font-body">Remarque :</label>
                                <textarea class="form-control custom-royal-input" id="remarque" name="remarque" rows="3" maxlength="250"><?= htmlspecialchars($remarque) ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-royal animate__animated animate__bounceIn"><i class="fas fa-sync-alt"></i> Modifier</button>
                                <button type="reset" class="btn btn-secondary">Annuler</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <?php include('../include/footer.inc.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
    <script src="../js/monJS.js"></script>
</body>

</html>