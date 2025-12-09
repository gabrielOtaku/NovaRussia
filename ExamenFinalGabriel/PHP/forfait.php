<?php
session_start();
$page_title = 'Nos Forfaits';
require_once('../include/config.inc.php');
include('functions.php');

$forfaits = [];
$error = '';

try {
    $pdo = connecterBD();
    $forfaits = obtenirForfaits($pdo);
} catch (\PDOException $e) {
    $error = "Erreur de base de données lors du chargement des forfaits. Veuillez réessayer plus tard.";
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../Images/russia.ico">
    <title>Nos Forfaits - Service d'aide NovaRussia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=EB+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/theme.css">

    <style>
        @media print {

            .custom-royal-header,
            .custom-royal-footer,
            .btn-print,
            .btn-royal {
                display: none !important;
            }

            .container {
                margin-top: 0 !important;
            }

            body {
                background-image: none !important;
            }
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
                    <h2 class="text-center custom-font-title mb-4 text-primary">Nos Forfaits de Service 📋</h2>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="custom-font-body">Voici la liste des différents services offerts. Si vos besoins ne figurent pas dans la liste, communiquez avec nous pour nous en faire part.</p>
                        <a href="javascript:window.print()" class="btn btn-sm btn-royal btn-print animate__animated animate__flash">
                            <i class="fas fa-print"></i> Imprimer
                        </a>
                    </div>

                    <?php if ($error): ?>
                        <p class="error-message animate__animated animate__shakeX"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></p>
                    <?php elseif (empty($forfaits)): ?>
                        <p class="text-center custom-font-body">Aucun forfait disponible pour le moment.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-royal align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Forfait</th>
                                        <th>Description</th>
                                        <th class="text-end">$$$</th>
                                    </tr>
                                </thead>
                                <tbody class="custom-font-body">
                                    <?php foreach ($forfaits as $forfait): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($forfait['nom']) ?></td>
                                            <td><?= htmlspecialchars($forfait['description']) ?></td>
                                            <td class="text-end"><?= number_format($forfait['prix'], 2, '.', '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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