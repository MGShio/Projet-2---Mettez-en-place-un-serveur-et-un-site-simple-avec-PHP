<?php
// Connexion à la base de données
require 'bdd.php'; // Inclure le fichier de connexion
$connexion = connexion(); // Utiliser la fonction de connexion

// Inclure le fichier oeuvres.php pour accéder aux œuvres initiales
require 'oeuvres.php';

// Insérer les œuvres initiales si elles ne sont pas déjà présentes
foreach ($oeuvres as $oeuvre) {
    // Vérifier si l'œuvre existe déjà
    $stmt = $connexion->prepare('SELECT id FROM oeuvres WHERE titre = :titre AND artiste = :artiste');
    $stmt->execute([
        ':titre' => $oeuvre['titre'],
        ':artiste' => $oeuvre['artiste']
    ]);
    
    if (!$stmt->fetch()) {
        // Insérer l'œuvre si elle n'existe pas
        $insert = $connexion->prepare('INSERT INTO oeuvres (titre, artiste, image, description) VALUES (:titre, :artiste, :image, :description)');
        $insert->execute([
            ':titre' => $oeuvre['titre'],
            ':artiste' => $oeuvre['artiste'],
            ':image' => $oeuvre['image'],
            ':description' => $oeuvre['description']
        ]);
    }
}

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