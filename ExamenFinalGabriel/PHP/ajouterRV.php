<?php
session_start();
$page_title = 'Ajouter un Rendez-vous';
require_once('../include/config.inc.php');
include('functions.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: connexion.php");
    exit();
}

$idClient = $_SESSION['idClient'];
$error = '';
$dateRV = '';
$endroit = '';
$remarque = '';
$noForfait = '';
$min_date = date('Y-m-d', strtotime('+2 days')); // Règle des 2 jours

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noForfait = trim($_POST['noForfait'] ?? '');
    $dateRV = trim($_POST['dateRV'] ?? '');
    $endroit = trim($_POST['endroit'] ?? '');
    $remarque = trim($_POST['remarque'] ?? '');

    if (empty($noForfait) || empty($dateRV)) {
        $error = 'Veuillez sélectionner un forfait et une date.';
    } elseif (!strtotime($dateRV)) {
        $error = 'Format de date invalide.';
    } elseif ($dateRV < $min_date) {
        $error = "Les rendez-vous doivent être pris 2 jours après aujourd'hui. Date minimale: " . date('Y-m-d', strtotime($min_date));
    } else {
        try {
            $pdo = connecterBD();

            $stmt_forfait = $pdo->prepare("SELECT idForfait FROM forfait WHERE idForfait = ?");
            $stmt_forfait->execute([$noForfait]);

            if (!$stmt_forfait->fetch()) {
                $error = 'Forfait sélectionné invalide.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO rendezvous (noClient, noForfait, dateRV, endroit, remarque) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$idClient, $noForfait, $dateRV, $endroit, $remarque]);

                header("Location: rendezvous.php?success=ajoutRV");
                exit();
            }
        } catch (\PDOException $e) {
            $error = "Erreur de base de données lors de l'ajout du rendez-vous.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/russia.ico">
    <title>Ajouter un Rendez-vous - Service d'aide NovaRussia</title>

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
                    <h2 class="text-center custom-font-title mb-4 text-primary">Planifier un Nouveau Rendez-vous 📅</h2>

                    <p class="text-center custom-font-body">Les rendez-vous doivent être pris **2 jours après aujourd'hui**.</p>

                    <form method="POST" action="ajouterRV.php" class="mt-4 form-ajouter-rv">

                        <?php if ($error): ?>
                            <p class="error-message animate__animated animate__shakeX"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="forfait" class="form-label custom-font-body">Forfait:</label>
                            <?php
                            try {
                                $pdo = connecterBD();
                                echo genererListeForfaits($pdo, $noForfait);
                            } catch (\PDOException $e) {
                                echo '<p class="text-danger">Erreur de connexion pour les forfaits.</p>';
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label for="dateRV" class="form-label custom-font-body">Date:</label>
                            <input type="date" class="form-control custom-royal-input" id="dateRV" name="dateRV"
                                required min="<?= htmlspecialchars($min_date) ?>" value="<?= htmlspecialchars($dateRV) ?>">
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
                            <button type="submit" class="btn btn-royal animate__animated animate__pulse"><i class="fas fa-plus-circle"></i> Ajouter</button>
                            <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Annuler</button>
                        </div>
                    </form>
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