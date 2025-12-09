<?php
$base_path = '../';

echo '<header class="custom-royal-header shadow-lg">';
echo '<nav class="navbar navbar-expand-lg">';


echo '<div class="container-fluid">';
echo '<a class="navbar-brand animate__animated animate__fadeInLeft" href="' . $base_path . 'index.php">';
echo '<img src="' . $base_path . 'Images/poutine.png" alt="Logo Service d\'aide NovaRussia" width="120" height="60" class="d-inline-block align-text-top custom-logo-shadow">';
echo '</a>';
echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">';
echo '<span class="navbar-toggler-icon"></span>';
echo '</button>';
echo '<div class="collapse navbar-collapse" id="navbarNav">';
echo '<ul class="navbar-nav ms-auto">';

//  Accueil
echo '<li class="nav-item">';
echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Accueil') ? 'active' : '') . '" href="' . $base_path . 'index.php">Accueil</a>';
echo '</li>';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Mes Rendez-vous
    echo '<li class="nav-item">';
    echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Rendez-vous') ? 'active' : '') . '" href="' . $base_path . 'php/rendezVous.php">Mes Rendez-vous</a>';
    echo '</li>';

    //  Ajouter un rendez-vous
    echo '<li class="nav-item">';
    echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Ajouter un Rendez-vous') ? 'active' : '') . '" href="' . $base_path . 'php/ajouterRV.php">Ajoutez un rendez-vous</a>';
    echo '</li>';

    //  Contact 
    echo '<li class="nav-item">';
    echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Contact') ? 'active' : '') . '" href="' . $base_path . 'php/contact.php">Contact</a>';
    echo '</li>';

    // Bouton Déconnexion
    echo '<li class="nav-item">';
    echo '<a class="btn btn-danger btn-sm ms-2 mt-1 animate__animated animate__flash" href="' . $base_path . 'php/rendezVous.php?deconnexion=1">Déconnexion</a>';
    echo '</li>';
} else {
    //  Nos forfaits
    echo '<li class="nav-item">';
    echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Nos Forfaits') ? 'active' : '') . '" href="' . $base_path . 'php/forfait.php">Nos forfaits</a>';
    echo '</li>';

    //  S'inscrire
    echo '<li class="nav-item">';
    echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Inscription') ? 'active' : '') . '" href="' . $base_path . 'php/inscription.php">S\'inscrire</a>';
    echo '</li>';

    //  Contact 
    echo '<li class="nav-item">';
    echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Contact') ? 'active' : '') . '" href="' . $base_path . 'php/contact.php">Contact</a>';
    echo '</li>';

    //  Mon espace guerrier
    echo '<li class="nav-item">';
    echo '<a class="nav-link custom-nav-link ' . (($page_title == 'Connexion') ? 'active' : '') . '" href="' . $base_path . 'php/connexion.php">Mon espace guerrier</a>';
    echo '</li>';
}

echo '</ul>';
echo '</div>';
echo '</div>';
echo '</nav>';
echo '</header>';
