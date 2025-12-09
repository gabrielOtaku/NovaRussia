document.addEventListener("DOMContentLoaded", () => {
  // --- 1. Gestion du Preloader et de la Transition de Page  ---
  const preloader = document.getElementById("preloader");
  if (preloader) {
    window.addEventListener("load", () => {
      preloader.style.opacity = "0";
      setTimeout(() => {
        preloader.style.display = "none";
      }, 500);
    });
  }

  // --- 2. Confirmation de Suppression des Rendez-vous ---
  const deleteButton = document.getElementById("btn-supprimer-rv");
  if (deleteButton) {
    deleteButton.addEventListener("click", (e) => {
      const checkboxes = document.querySelectorAll(
        'input[name="rv_a_supprimer[]"]:checked'
      );
      if (checkboxes.length === 0) {
        alert("Veuillez sélectionner au moins un rendez-vous à supprimer.");
        e.preventDefault();
        return;
      }
      [cite_start];
      if (
        !confirm("Êtes-vous certain de vouloir supprimer ces rendez-vous ?")
      ) {
        e.preventDefault();
      }
    });
  }

  // --- 3. Gestion des Boutons ANNULER / Reset  ---
  document.querySelectorAll('button[type="reset"]').forEach((button) => {
    button.addEventListener("click", (e) => {
      if (document.querySelector(".form-modifier-rv")) {
        e.preventDefault();
        window.location.href = "rendezVous.php";
      } else {
        e.target.closest("form").reset();
        e.preventDefault();
      }
    });
  });

  // --- 4. Popups de Succès/Bienvenue  ---
  const urlParams = new URLSearchParams(window.location.search);
  const successType = urlParams.get("success");
  const welcomeType = urlParams.get("welcome");

  if (successType || welcomeType) {
    let title = "";
    let message = "";
    let icon = "";

    if (successType === "inscription") {
      title = "👑 Bienvenue Guerrier!";
      message =
        "Votre inscription a été un succès. Vous pouvez maintenant vous connecter.";
      icon =
        '<i class="fas fa-crown text-warning mb-2 animate__animated animate__tada"></i>';
    } else if (welcomeType === "1") {
      const prenom = urlParams.get("prenom") || "Cher Guerrier";
      title = "⚔️ Connexion Réussie!";
      message = `Bienvenue à vous, ${prenom}. Votre espace personnel est prêt.`;
      icon =
        '<i class="fas fa-shield-alt text-primary mb-2 animate__animated animate__swing"></i>';
    } else if (successType === "ajoutRV") {
      title = "✅ Rendez-vous Ajouté!";
      message =
        "Votre nouveau rendez-vous a été enregistré dans notre système.";
      icon =
        '<i class="fas fa-calendar-check text-success mb-2 animate__animated animate__flipInX"></i>';
    } else if (successType === "modifRV") {
      title = "📝 Rendez-vous Modifié!";
      message = "Les informations de votre rendez-vous ont été mises à jour.";
      icon =
        '<i class="fas fa-edit text-info mb-2 animate__animated animate__flipInX"></i>';
    } else if (successType === "supprRV") {
      title = "🗑️ Rendez-vous Supprimé!";
      message = "Les rendez-vous sélectionnés ont été effacés.";
      icon =
        '<i class="fas fa-check-circle text-danger mb-2 animate__animated animate__flipInX"></i>';
    }

    if (title) {
      document.getElementById("modalTitle").innerHTML = title;
      document.getElementById(
        "modalBody"
      ).innerHTML = `<p class="text-center">${icon} ${message}</p>`;
      const successModal = new bootstrap.Modal(
        document.getElementById("successModal")
      );
      successModal.show();

      history.pushState(
        null,
        "",
        window.location.pathname +
          window.location.search
            .replace(/[\?&]success=[^&]*/, "")
            .replace(/[\?&]welcome=[^&]*/, "")
      );
    }
  }
});
