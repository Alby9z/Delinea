<?php

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Inclure les fichiers nécessaires de PHPMailer
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/../config.php'; // Pour inclure les constantes SMTP_USERNAME et SMTP_PASSWORD

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $prenom = isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom'], ENT_QUOTES, 'UTF-8') : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '';
    $service = isset($_POST['service']) ? htmlspecialchars($_POST['service'], ENT_QUOTES, 'UTF-8') : '';
    $message = isset($_POST['text']) ? htmlspecialchars($_POST['text'], ENT_QUOTES, 'UTF-8') : '';

    // Vérifier que les champs requis sont remplis
    if (empty($prenom) || empty($email) || empty($service)) {
        echo 'Veuillez remplir tous les champs obligatoires.';
        exit;
    }    

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.ionos.fr';                      
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = SMTP_USERNAME; // Nom d'utilisateur SMTP (défini dans config.php)
        $mail->Password   = SMTP_PASSWORD; // Mot de passe SMTP (défini dans config.php)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
        $mail->Port       = 587;                                   
        $mail->CharSet    = 'UTF-8'; // Pour gérer les accents

        // Définir l'expéditeur et le destinataire
        $mail->setFrom('info_contact@xn--dlin-massage-bkb.fr', 'Délinéa-massages');
        $mail->addAddress('lukas.dias2004@gmail.com'); // Adresse de réception

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = "Nouvelle demande de contact - Service: $service";

        // Contenu HTML
        $mail->Body    = "
            <h2>Nouvelle demande de contact</h2>
            <p><strong>Prénom : </strong> $prenom</p>
            <p><strong>Email : </strong> $email</p>
            <p><strong>Service choisi : </strong> $service</p>
            <p><strong>Message : </strong> $message</p>
        ";

        // Contenu texte alternatif pour les clients email qui ne supportent pas le HTML
        $mail->AltBody = "
            Nouvelle demande de contact\n
            Prénom : $prenom\n
            Email : $email\n
            Service choisi : $service\n
            Message : $message
        ";

        // Envoyer l'email
        $mail->send();
        echo 'Votre demande a bien été envoyée.';
    } catch (Exception $e) {
        echo "L'envoi de l'email a échoué : {$mail->ErrorInfo}";
    }
} else {
    echo 'Méthode de requête non supportée.';
}
?>
