<?php include "connection.php"; ?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Send - Tags</title>
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
         * Cette page est similaire à wall.php ou feed.php 
         * mais elle porte sur les mots-clés (tags)
         */
        /**
         * Etape 1: Le mur concerne un mot-clé en particulier
         */
        $tagId = intval($_GET['tag_id']);
        ?>


        <aside>
            <?php
            /**
             * Etape 3: récupérer le nom du mot-clé
             */
            $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $tag = $lesInformations->fetch_assoc();
            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par le label et effacer la ligne ci-dessous
            ?>
            <img src="user.png" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages comportant
                    le mot-clé <?php echo $tag['label']; ?> (n° <?php echo $tagId ?>)
                </p>

            </section>
        </aside>
        <main>
            <?php
            /**
             * Etape 3: récupérer tous les messages avec un mot clé donné
             */
            $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
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

                // echo "<pre>" . print_r($post, 1) . "</pre>";
            ?>
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post['created']; ?></time>
                    </h3>
                    <address>Par <?php echo $post['author_name']; ?></address>
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