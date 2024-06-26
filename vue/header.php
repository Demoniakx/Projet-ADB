<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/CSS/style.css">
    <link rel="stylesheet" href="../asset/CSS/mediaqueries.css">
    <title><?php echo $onglet ?></title>

</head>
<body>        
    <header>
        <?php session_start(); ?>
        <div>
            <img class='imgheader' src="../asset/Image/Thomas-cooking_1.svg" alt="">
        </div>
        <div>
            <nav>
                <ul class="liste">
                    <li><a href="paccueil.php">Accueil</a></li>
                    <?php
                        if(!isset($_SESSION['user'])){
                    ?>
                            <li><a href="pconnexion.php">Ajouter une recette</a></li>
                    <?php
                       }else{
                        ?>
                            <li><a href="pajoutrecette.php">Ajouter une recette</a></li>
                        <?php    }
                    ?>

                    <?php
                        if(!isset($_SESSION['user'])){
                            ?>
                            <li><a href="pconnexion.php">Connexion</a></li>
                            <?php 
                        }else{
                            ?>
                            <li><a href="pdeconnect.php">Déconnexion</a></li>
                        <?php }
                        if(isset($_SESSION['user'])){
                            if($_SESSION['user']['role'] == '1'){
                                ?>
                                <li><a href="padmin.php">Espace administrateur</a></li>
                                <?php
                            }elseif($_SESSION['user']['role'] == "0"){
                                ?>
                                <li><a href="pmodificationdonneesuser.php">Espace utilisateur</a></li>
                                <?php
                            }
                        }else{
                            ?>
                            <li><a href="pconnexion.php">Espace utilisateur</a></li>
                            <?php   
                        }
                    ?>
                </ul>
            </nav>
        </div>
    </header>