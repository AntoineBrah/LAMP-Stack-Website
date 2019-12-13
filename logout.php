<?php
session_start();

// Suppression des variables de session et de la session
$_SESSION = array();
session_destroy();

/* Cette page permet la déconnexion d'un utilisateur par le clic */
// On définit une Alertbox en PHP
function phpAlertLogout($msg) {
    echo '<script type="text/javascript">
        alert("' . $msg . '");
        window.location.href=\'index.php\';
    </script>';
}

phpAlertLogout('Déconnexion réussie.');
?>