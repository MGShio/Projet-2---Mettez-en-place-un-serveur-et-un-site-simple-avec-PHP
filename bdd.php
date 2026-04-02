<?php
function connexion() {
    $serveur = 'localhost';
    $utilisateur = 'root';
    $motdepasse = null;
    $basedonnees = 'oeuvres_db';

    try {
        $connexion = new PDO("mysql:host=$serveur;dbname=$basedonnees", $utilisateur, $motdepasse);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connexion;
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}
?>