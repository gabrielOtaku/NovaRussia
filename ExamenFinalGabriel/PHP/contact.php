<?php
session_start();
$page_title = 'Contact';
require_once('../include/config.inc.php');
include('functions.php');

$recipient = "info@poutine.org";
$subject = urlencode("Demande d'informations - Service d'aide NovaRussia");
$body = urlencode("Bonjour, je souhaite obtenir des informations concernant...");

$mailto_link = "mailto:{$recipient}?subject={$subject}&body={$body}";

// Liens directs pour les clients web
$outlook_link = "https://outlook.live.com/mail/0/deeplink/compose?to={$recipient}&subject={$subject}&body={$body}";
$gmail_link = "https://mail.google.com/mail/u/0/?view=cm&fs=1&to={$recipient}&su={$subject}&body={$body}";

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/russia.ico">
    <title>Contact - Service d'aide NovaRussia</title>

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
                <div class="col-lg-8 custom-form-container p-5 animate__animated animate__fadeInDown">
                    <h1 class="text-center custom-font-title mb-4 text-primary">Contacter le Kremelin 📞</h1>

                    <p class="text-center custom-font-body" style="font-size: 1.1em;">
                        Pour toute demande de service ou question sur votre adhésion, veuillez choisir votre méthode de contact préférée ci-dessous.
                    </p>

                    <h3 class="custom-font-title text-center mt-5 mb-4">Choisir votre service de messagerie :</h3>

                    <div class="d-grid gap-3 col-md-8 mx-auto">

                        <a href="<?= htmlspecialchars($outlook_link) ?>" target="_blank" class="btn btn-lg btn-primary btn-royal animate__animated animate__bounceInLeft">
                            <i class="fas fa-envelope me-2"></i> Ouvrir avec Outlook (Web)
                        </a>

                        <a href="<?= htmlspecialchars($gmail_link) ?>" target="_blank" class="btn btn-lg btn-danger btn-royal animate__animated animate__bounceInRight">
                            <i class="fas fa-envelope-square me-2"></i> Ouvrir avec Gmail (Web)
                        </a>

                        <hr class="my-4">

                        <a href="<?= htmlspecialchars($mailto_link) ?>" class="btn btn-lg btn-secondary btn-royal animate__animated animate__fadeInUp">
                            <i class="fas fa-mail-bulk me-2"></i> Utiliser votre client de messagerie par défaut
                            <small class="d-block mt-1">(Outlook, Thunderbird, Mail, etc.)</small>
                        </a>
                    </div>



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