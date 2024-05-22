<?php
    session_start();
    require('../model/userModel.php');

//si le bouton inscription a été envoyé.
if(isset($_POST['bInscription'])){
    $email = htmlspecialchars(trim($_POST['email']));
    $username = htmlspecialchars(trim($_POST['username']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $password = htmlspecialchars(trim($_POST['pwd']));
    $confirmedpassword = htmlspecialchars(trim($_POST['pwdconfirmed']));
    $regexemail = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/";

    //si les données récupérées ne sont pas vides
    if (!empty($email) && !empty($username) && !empty($lastname) && !empty($firstname) && !empty($password) && !empty($confirmedpassword))   {    

        //si les mots de passes correspondent
        if($password === $confirmedpassword){
            if(strlen($password) < 8){
                $error["8"] = "Le mot de passe doit contenir au moins 8 caractères.";
                header("Location: ../vue/pinscription.php?message=" . $error["8"]);
                exit;
            }
            
            //si le mot de passe de passe n'est pas vide lors de l'inscription et que l'adresse mail a un format valide
            if(!preg_match($regexemail, $email)){
                //Si l'adresse mail n'est pas valide
                $error["mail"] = "L'adresse email '$email' n'est pas valide.";
                //On transmet le message par l'url avec la redirection
                header("Location: ../vue/pinscription.php?message=" . $error["mail"]);
                exit;
            }
            $password = password_hash($password, PASSWORD_DEFAULT);
            // On transmet à la fonction "insertUser" les données à introduire en base de données, si l'inscription a rencontré un problème, on envoi un message a l'utilisateur
            $message = insertUser($email, $username, $lastname, $firstname, $password);
            if (isset($message)){
                //On transmet le message par l'url avec la redirection
                header("Location: ../vue/pinscription.php?message=" . $message);
                exit;
            }
            //On redirige l'utilisateur vers la page de connexion et transmet à l'utilisateur la réussite de l'inscription
            header("Location: ../vue/pconnexion.php?success");
            exit;
        }else{
            //Si les mots de passes ne correspondent pas
            $error["password"] = "Les mots de passes ne correspondent pas.";
            //On transmet le message par l'url avec la redirection
            header("Location: ../vue/pinscription.php?message=" . $error['password']);
            exit;
        }
    }else{
        //Si les champs ne sont pas remplis
        $error["empty"] = "Veuillez remplir tous les champs.";
        header("Location: ../vue/pinscription.php?message=" . $error['empty']);
    }
}
//Si le bouton modifier a été envoyé
elseif(isset($_POST['bModifyuser'])){
    $id = $_SESSION['user']['id'];
    $password = htmlspecialchars(trim($_POST['password']));
    $username = $_SESSION['user']['username'];
    $email = htmlspecialchars(trim($_POST['mail']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    if(!empty($email) && !empty($lastname) && !empty($firstname)){
        update($id, $email, $lastname, $firstname);
            // On détruit l'ancienne session
            session_destroy();
            // On démarre une session 
            session_start();
            // On récupère les données de l'utilisateur grâce a la fonction login (qui permet de créer une session avec les données utilisateur)
            login($username, $password);
            // On redirige vers l'accueil avec un message de réussite
            header("Location: ../vue/pmodificationdonneesuser.php?success");
            exit;
    }

} else if (isset($_POST['bConnexion'])) {
    //On récupère le login et le mdp
    $username = htmlspecialchars(trim($_POST['login']));
    $password = htmlspecialchars(trim($_POST['password']));
    if(!empty($username) && !empty($password)){
        //On appelle la fonction login qui permet de créer une session avec les données utilisateur
        $message = login($username, $password);
        //On le redirige vers l'accueil
        if(isset($message)){
            header("Location: ../vue/pconnexion.php?message=" . $message);
            exit;
        }else{
            header("Location: ../vue/paccueil.php");
            exit;
        }
    }else{
        //Si les champs ne sont pas remplis
        $error["empty"] = "Veuillez remplir tous les champs.";
        header("Location: ../vue/pconnexion.php?message=" . $error['empty']);
    }
}

//Si le bouton Ajouter pour la page ajout recette a été envoyé
if(isset($_POST['bAddrecipe'])){
    $title = htmlspecialchars(trim($_POST['title']));
    $cookingtools = htmlspecialchars(trim($_POST['cookingtools']));
    $ingredients = htmlspecialchars(trim($_POST['ingredients']));
    $person = htmlspecialchars(trim($_POST['person']));
    $recipe = htmlspecialchars(trim($_POST['recipe']));
    $author = $_SESSION['user']['username'];
    $idUser = $_SESSION['user']['id'];
    $date_create = date('y-m-d');

    if(!empty($title) && !empty($cookingtools) && !empty($ingredients) && !empty($person) && !empty($recipe)){
        $message = InsertRecipe($title, $cookingtools, $ingredients, $person, $recipe, $author, $idUser, $date_create);
        //vérification s'il y a un message d'erreur
        if(isset($message)){
            //On transmet le message par l'url avec la redirection
            header("Location: ../vue/pajoutrecette.php?message=" . $message);
        }
        //On redirige et transmet à l'utilisateur la réussite de l'ajout de la recette
        header("Location: ../vue/paccueil.php?success");
        exit;
    }

} else if(isset($_POST['bModifyrecipe'])){
    $message = Modifyrecipe($title, $cookingtools, $ingredients, $person, $recipe, $author);
    //vérification s'il y a un message d'erreur
    if(isset($message)){
    //On transmet le message par l'url avec la redirection
    header("Location: ../vue/pmodifierrecette.php?message=" . $message);
    }
    //On redirige et transmet à l'utilisateur la réussite de la modification de la recette
    header("Location: ../vue/pmodifierrecette.php?success");
    exit;
}else if(isset($_POST['bModifypwd'])){
    $nb1 = $_POST['nb1'];
    $nb2 = $_POST['nb2'];
    $resultat = htmlspecialchars(trim($_POST['resultat']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['newpassword']));
    $confirmedpassword = htmlspecialchars(trim($_POST['newconfirmedpassword']));
    if(isset($password) && isset($confirmedpassword) && isset($resultat) && isset($username)){

        if($password === $confirmedpassword && $resultat == $nb1 + $nb2){ 
            $password = password_hash($password, PASSWORD_DEFAULT);
            $message = pwdforget($username, $password);
            if(isset($message)){
                header("Location: ../vue/pmdpoublie.php?message=" . $message);
                exit;
            }else{
                header("Location: ../vue/pconnexion.php?success");
                exit;
            }
        }
    }else{
        $error["empty"] = "Veuillez remplir tous les mots de passe.";
        header("Location: ../vue/pmdpoublie.php?message=" . $error['empty']);}
}
?>