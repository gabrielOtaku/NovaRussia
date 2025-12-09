<?php
require_once('../include/config.inc.php');


function genererListeForfaits($pdo, $selectedId = null)
{
    try {
        $stmt = $pdo->query("SELECT idForfait, nom FROM forfait ORDER BY nom");
        $forfaits = $stmt->fetchAll();

        $html = '<select name="noForfait" id="forfait" class="form-select custom-royal-input" required>';
        $html .= '<option value="">Sélectionnez un forfait...</option>';
        foreach ($forfaits as $forfait) {
            $selected = ($selectedId == $forfait['idForfait']) ? 'selected' : '';
            $html .= '<option value="' . htmlspecialchars($forfait['idForfait']) . '" ' . $selected . '>' . htmlspecialchars($forfait['nom']) . '</option>';
        }
        $html .= '</select>';
        return $html;
    } catch (\PDOException $e) {
        return '<p class="text-danger">Erreur de base de données lors du chargement des forfaits: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}

function obtenirForfaits($pdo)
{
    $stmt = $pdo->query("SELECT idForfait, nom, description, prix FROM forfait ORDER BY idForfait");
    return $stmt->fetchAll();
}

function obtenirRVClient($pdo, $idClient)
{
    $stmt = $pdo->prepare("
        SELECT 
            rv.idRV, 
            rv.dateRV, 
            rv.endroit, 
            rv.remarque, 
            f.nom AS nomForfait
        FROM 
            rendezvous rv
        JOIN 
            forfait f ON rv.noForfait = f.idForfait
        WHERE 
            rv.noClient = :idClient
        ORDER BY 
            rv.dateRV DESC
    ");
    $stmt->execute(['idClient' => $idClient]);
    return $stmt->fetchAll();
}

function obtenirRVParId($pdo, $idRV, $idClient)
{
    $stmt = $pdo->prepare("
        SELECT 
            rv.idRV, 
            rv.noForfait, 
            rv.dateRV, 
            rv.endroit, 
            rv.remarque
        FROM 
            rendezvous rv
        WHERE 
            rv.idRV = :idRV AND rv.noClient = :idClient
    ");
    $stmt->execute(['idRV' => $idRV, 'idClient' => $idClient]);
    return $stmt->fetch();
}
