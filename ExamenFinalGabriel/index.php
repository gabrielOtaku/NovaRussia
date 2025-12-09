<?php
session_start();
$page_title = 'Accueil';
$base_path = '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="Images/russia.ico">
    <title>Service d'aide NovaRussia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=EB+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/theme.css">
</head>

<body>
    <div id="preloader">
        <div class="text-center">
            <div class="loader"></div>
            <p class="mt-3 text-white custom-font-title animate__animated animate__pulse animate__infinite" style="letter-spacing: 2px;">Chargement NovaRussia</p>
        </div>
    </div>

    <div class="page-content-fadein">

        <?php include('include/head.inc.php'); ?>

        <main class="container py-5 mt-5">
            <section class="row justify-content-center">
                <div class="col-lg-8 custom-form-container p-5 animate__animated animate__fadeInDown">

                    <h1 class="text-center custom-font-title mb-4 text-primary animate__animated animate__fadeIn">Bienvenue sur le service d'aide de NovaRussia</h1>

                    <div class="text-center mb-4">
                        <img src="Images/poutine.png" alt="Service d'aide NovaRussia" class="img-fluid custom-logo-shadow" style="max-width: 250px;">
                    </div>

                    <div class="text-justified custom-font-body" style="font-size: 1.1em;">
                        <p class="animate__animated animate__fadeInUp animate__delay-1s">Le service d'aide NovaRussia est un organisme subventionné par le Kremelin qui offre, à la communauté, différents forfaits au goût du jour et ce, depuis 2001.</p>
                        <p class="animate__animated animate__fadeInUp animate__delay-1s">Pour bénéficier de ces forfaits ou pour offrir du temps comme bénévole en Ukraine, vous devez communiquer avec nous, ou vous
                            <a href="php/inscription.php" class="custom-gold-link animate__animated animate__pulse animate__infinite">inscrire</a>
                            en remplissant notre formulaire en ligne.
                        </p>
                        <p class="text-center custom-font-title mt-4 text-primary animate__animated animate__heartBeat animate__delay-2s">Soyez en paix ! Nous voulons votre bien !!!</p>

                        <div class="text-center mt-5 p-3" style="border: 1px dashed var(--color-accent);">
                            <p class="mb-0">Pour toute question: <a href="PHP/contact.php" class="custom-gold-link">info@poutine.org</a></p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php include('include/footer.inc.php'); ?>

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

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
    <script src="js/monJS.js"></script>

</body>

</html>