

<!-- Conteneur de navigation-->
<div id='navbar'>
    <div id='navbar-content'>
        <i class="material-icons" id='mobile-button' style="font-size:36px">menu</i> <!-- Responsive web design-->
        <h1 class='logo'>
            <a href='#' title='Evenementia, recherche et création d&#39;événements en toute simplicité'>
                <img src='logo.png' width='160' height='35'>
            </a>
        </h1>
        <nav>
            <div class='nav-event'>
                <button class='drop-button'><i style='font-size:18px' class='far'>&#xf328;</i>Créer/Rechercher</button>
                <div class='nav-event-dropdown'>
                    <a href='#' id='#'>Créer</a>
                    <a href='#'>Rechercher</a>
                </div>
            </div>

            <div class='nav-identification2'>
                <?php echo '<button class=\'online-button\'><i class=\'fas fa-user-check\' style=\'font-size:18px\'></i>' . $_SESSION['Pseudo'] . '</button>' ?>
                <div class='nav-identification2-dropdown'>
                    <a href='#' id='#'>Mon profil</a>
                    <a href='logout.php'>Déconnexion</a>
                </div>
            </div>
            
        </nav>
    </div>
</div>