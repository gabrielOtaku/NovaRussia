<?php
session_start();
$page_title = 'Inscription';
require_once('../include/config.inc.php');
include('functions.php');
$error = '';
$success = false;
$prenom = '';
$nom = '';
$courriel = '';
$telephone = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $courriel = trim($_POST['courriel'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $motPasse = $_POST['motPasse'] ?? '';
    $photo = $_FILES['photo'] ?? null;

    // Validation de base
    if (empty($prenom) || empty($nom) || empty($courriel) || empty($motPasse)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse courriel invalide.';
    }
    // Validation longueur mot de passe
    if (strlen($motPasse) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        try {
            $pdo = connecterBD();
            $pdo->beginTransaction();

            // Insertion du mot de passe en CLAIR
            $stmt = $pdo->prepare("INSERT INTO client (prenom, nom, courriel, telephone, motPasse) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$prenom, $nom, $courriel, $telephone, $motPasse]);

            $idClient = $pdo->lastInsertId();

            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $mime = mime_content_type($photo['tmp_name']);
                $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $ext = $extensions[$mime] ?? 'jpg';

                $upload_dir = '../Images/';
                $new_file_name = $idClient . '.' . $ext;
                $destination = $upload_dir . $new_file_name;

                if (!move_uploaded_file($photo['tmp_name'], $destination)) {
                    throw new \Exception("Erreur lors de la copie de l'image.");
                }
            }

            $pdo->commit();
            $success = true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == '23000') {
                $error = 'Adresse courriel existante. Erreur inscription du client';
            } else {
                $error = "Erreur de base de données. L'enregistrement n'a pas été effectué. Code: " . $e->getCode();
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            $error = "Erreur système: " . $e->getMessage();
        }
    }

    if ($success) {
        header("Location: connexion.php?success=inscription");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/russia.ico">
    <title>Inscription - Service d'aide NovaRussia</title>

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
                <div class="col-lg-8 custom-form-container p-5 animate__animated animate__zoomIn">
                    <h2 class="text-center custom-font-title mb-4 text-primary">Formulaire d'Inscription Royale 👑</h2>
                    <p class="text-center custom-font-body">Pour avoir accès au service d'une personne bénévole, vous devez être inscrit sur notre site.</p>

                    <form method="POST" action="inscription.php" enctype="multipart/form-data" class="mt-4 form-inscription">

                        <?php if ($error): ?>
                            <p class="error-message animate__animated animate__shakeX"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="prenom" class="form-label custom-font-body">Prénom :</label>
                            <input type="text" class="form-control custom-royal-input" id="prenom" name="prenom" required maxlength="25" value="<?= htmlspecialchars($prenom) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label custom-font-body">Nom :</label>
                            <input type="text" class="form-control custom-royal-input" id="nom" name="nom" required maxlength="25" value="<?= htmlspecialchars($nom) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="courriel" class="form-label custom-font-body">Courriel :</label>
                            <input type="email" class="form-control custom-royal-input" id="courriel" name="courriel" required maxlength="50" value="<?= htmlspecialchars($courriel) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label custom-font-body">Téléphone :</label>
                            <input type="tel" class="form-control custom-royal-input" id="telephone" name="telephone" maxlength="10" pattern="[0-9]{10}" placeholder="Ex: 4185554444" value="<?= htmlspecialchars($telephone) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="motPasse" class="form-label custom-font-body">Choisir un mot de passe (min 8 caractères):</label>
                            <input type="password" class="form-control custom-royal-input" id="motPasse" name="motPasse" required minlength="8" maxlength="25">
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label custom-font-body">Ajouter une photo de vous :</label>
                            <input type="file" class="form-control custom-royal-input" id="photo" name="photo" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-start mt-4">
                            <button type="submit" class="btn btn-royal me-3 animate__animated animate__bounceIn">Sauvegarder</button>
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