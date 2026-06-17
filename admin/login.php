<?php
session_start();

require_once '../config/db.php';

$message_error = '';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    if (empty($email) || empty($mot_de_passe)) {
        $message_error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_error = "L'adresse email n'est pas valide.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email LIMIT 1");
        $stmt->execute([
            ':email' => $email
        ]);

        $admin = $stmt->fetch();

        if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_nom'] = $admin['nom'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_role'] = $admin['role'];

            header('Location: dashboard.php');
            exit;
        } else {
            $message_error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion admin - Site Garage</title>
    <link rel="stylesheet" href="css/style_admin.css">
</head>
<body class="admin-login-body">

    <main class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <span>Site</span> Garage
            </div>

            <h1>Connexion admin</h1>
            <p>Connectez-vous pour gérer le site du garage.</p>

            <?php if (!empty($message_error)): ?>
                <div class="admin-alert admin-alert-error">
                    <?= htmlspecialchars($message_error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="login-form">
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Adresse email"
                    required
                >

                <input 
                    type="password" 
                    name="mot_de_passe" 
                    placeholder="Mot de passe"
                    required
                >

                <button type="submit">Se connecter</button>
            </form>

            <a href="../index.php" class="back-site-link">Retour au site</a>
        </div>
    </main>

</body>
</html>