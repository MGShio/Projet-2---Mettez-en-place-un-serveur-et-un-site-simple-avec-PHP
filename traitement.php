<?php
// Connexion à la base de données
require 'bdd.php'; // Inclure le fichier de connexion
$connexion = connexion(); // Utiliser la fonction de connexion

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $titre = htmlspecialchars(trim($_POST['titre'] ?? ''));
    $artiste = htmlspecialchars(trim($_POST['artiste'] ?? ''));
    $image = htmlspecialchars(trim($_POST['image'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));

    // Vérifier que les champs obligatoires sont remplis et valides
    if (!empty($titre) && !empty($artiste) && strlen($description) >= 3 && filter_var($image, FILTER_VALIDATE_URL) && strpos($image, 'https://') === 0) {
        try {
            // Préparer et exécuter la requête d'insertion
            $requete = $connexion->prepare('INSERT INTO oeuvres (titre, artiste, image, description) VALUES (:titre, :artiste, :image, :description)');

            $requete->execute([
                ':titre' => $titre,
                ':artiste' => $artiste,
                ':image' => $image,
                ':description' => $description
            ]);

            header('Location: index.php?success=1');
            exit();
        } catch (PDOException $e) {
            header('Location: index.php?error=2'); // Erreur lors de l'insertion
            exit();
        }
    } else {
        header('Location: index.php?error=1'); // Champs obligatoires manquants
        exit();
    }
}
?>