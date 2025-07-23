<?php
// Configuration SMTP pour PHPMailer
// Fichier: app/config/email.php (corrigé pour le développement)
return [
    'smtp' => [
        'host' => 'mailhog', // Le nom du service Docker
        'port' => 1025,
        'encryption' => false, // Pas de chiffrement pour MailHog
        'username' => '',      // Pas d'authentification
        'password' => '',
        'from_email' => 'no-reply@univalid.test',
        'from_name' => 'Univalid (Dev)'
    ]
];