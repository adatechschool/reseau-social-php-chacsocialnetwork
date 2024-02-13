<?php include "connection.php"; ?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Send - Mon Mur</title>
    <link rel="shortcut icon" href="sendico.ico" type="image/x-icon">
    <meta name="author" content="Chac">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div class="headerPS">
        <a href="admin.php"><img src="sendico.png" alt="Logo de Send" width="30" height=auto></a>
        <h1>Send</h1>
    </div>
    <nav aria-label="Navigation">
        <ul>
            <li><a href="news.php">Actualités</a></li>
            <li><a href="wall.php?user_id=5">Mur</a></li>
            <li><a href="feed.php?user_id=5">Flux</a></li>
            <li><a href="tags.php?tag_id=1">Mots-clés</a></li>
            <li><a href="#">▾ Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=5">Paramètres</a></li>
                    <li><a href="followers.php?user_id=5">Mes followers</a></li>
                    <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="wrapper">
        <?php
        /**
         * Etape 1: Le mur concerne un utilisateur en particulier
         * La première étape est donc de trouver quel est l'id de l'utilisateur
         * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
         * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
         * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
         */
        $userId = intval($_GET['user_id']);
        ?>

        <aside>
            <?php
            /**
             * Etape 2: récupérer le nom de l'utilisateur
             */
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
            ?>
            <img src="user.png" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias']; ?>
                    (n° <?php echo $userId ?>)
                </p>
            </section>
        </aside>
        <main>
            <article>
                <?php
                /**
                 * TRAITEMENT DU FORMULAIRE
                 */
                // A : vérifier si on est en train d'afficher ou de traiter le formulaire
                // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                $enCoursDeTraitement = isset($_POST['auteur']);
                if ($enCoursDeTraitement) {
                    // on ne fait ce qui suit que si un formulaire a été soumis.
                    // B: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                    // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                    echo "<pre>" . print_r($_POST, 1) . "</pre>";
                    // et complétez le code ci dessous en remplaçant les ???
                    $authorId = $_POST['auteur'];
                    $postContent = $_POST['message'];


                    //C : Petite sécurité
                    // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $authorId = intval($mysqli->real_escape_string($authorId));
                    $postContent = $mysqli->real_escape_string($postContent);
                    //D : construction de la requete
                    $lInstructionSql = "INSERT INTO posts "
                        . "(id, user_id, content, created, parent_id) "
                        . "VALUES (NULL, "
                        . $authorId . ", "
                        . "'" . $postContent . "', "
                        . "NOW(), "
                        . "NULL);";
                    echo $lInstructionSql;
                    // E : execution
                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        echo "Impossible d'ajouter le message: " . $mysqli->error;
                    } else {
                        echo "Message posté en tant que :" . $listAuteurs[$authorId];
                    }
                }
                ?>
                <form action="wall.php" method="post">
                    <dt><label for='message'>Message</label></dt>
                    <dd><textarea name='message'></textarea></dd>
                    <input type='submit'>
                </form>
            </article>
            <?php
            /**
             * Etape 3: récupérer tous les messages de l'utilisatrice
             */
            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            /**
             * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
             */
            while ($post = $lesInformations->fetch_assoc()) {
                echo "<pre>" . print_r($post, 1) . "</pre>";
            ?>

                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post['created']; ?></time>
                    </h3>
                    <address>Par <a href="wall.php?user_id=<?php echo $follower['id'] ?>"><?php echo $post['author_name']; ?></a></address>
                    <div>
                        <p><?php echo $post['content']; ?></p>
                    </div>
                    <footer>
                        <small>❤️ <?php echo $post['like_number']; ?></small>
                        <a href="">#<?php echo $post['taglist']; ?></a>,
                    </footer>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>