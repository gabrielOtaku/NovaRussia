<?php
session_start();
$page_title = 'Connexion';
require_once('../include/config.inc.php');
include('functions.php');
$error = '';
$courriel = '';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: rendezVous.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courriel = trim($_POST['courriel'] ?? '');
    $motPasse = $_POST['motPasse'] ?? '';

    if (empty($courriel) || empty($motPasse)) {
        $error = 'Veuillez entrer votre courriel et mot de passe.';
    } else {
        try {
            $pdo = connecterBD();

            $stmt = $pdo->prepare("SELECT idClient, prenom, nom FROM client WHERE courriel = :courriel AND motPasse = :motPasse");
            $stmt->execute(['courriel' => $courriel, 'motPasse' => $motPasse]);
            $client = $stmt->fetch();


            if ($client) {
                $_SESSION['logged_in'] = true;
                $_SESSION['idClient'] = $client['idClient'];
                $_SESSION['prenom'] = $client['prenom'];
                $_SESSION['nom'] = $client['nom'];

                header("Location: rendezVous.php?welcome=1&prenom=" . urlencode($client['prenom']));
                exit();
            } else {
                $error = 'Le courriel et/ou le mot de passe sont invalide';
            }
        } catch (\PDOException $e) {
            $error = "Erreur de base de données. Veuillez réessayer plus tard.";
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
    <title>Connexion - Service d'aide NovaRussia</title>

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
                    <h2 class="text-center custom-font-title mb-4 text-primary">Accès à l'Espace Guerrier 🛡️</h2>
                    <p class="text-center custom-font-body">Cette section vous permet d'accéder à votre espace bénévole. Dans cette section, vous pourrez gérer vos rendez-vous.</p>

                    <form method="POST" action="connexion.php" class="mt-4 form-connexion">

                        <?php if ($error): ?>
                            <p class="error-message animate__animated animate__shakeX"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="courriel" class="form-label custom-font-body">Courriel:</label>
                            <input type="email" class="form-control custom-royal-input" id="courriel" name="courriel" required maxlength="50" value="<?= htmlspecialchars($courriel) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="motPasse" class="form-label custom-font-body">Mot de passe:</label>
                            <input type="password" class="form-control custom-royal-input" id="motPasse" name="motPasse" required maxlength="25">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-royal animate__animated animate__pulse">Se Connecter</button>
                            <button type="reset" class="btn btn-secondary">Annuler</button>
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