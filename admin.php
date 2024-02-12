<?php include "connection.php"; ?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Send - Admin</title>
    <link rel="shortcut icon" href="sendico.ico" type="image/x-icon">
    <meta name="author" content="Chac">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div class="headerPS">
        <a href="admin.php"><img src="resoc.png" alt="Logo de Pride Score" width="30" height=auto></a>
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

    <?php
    /**
     * Etape 1: Ouvrir une connexion avec la base de donnée.
     */
    //verification
    if ($mysqli->connect_errno) {
        echo ("Échec de la connexion : " . $mysqli->connect_error);
        exit();
    }
    ?>
    <div id="wrapper" class='admin'>
        <aside>
            <h2>Mots-clés</h2>
            <?php
            /*
                 * Etape 2 : trouver tous les mots clés
                 */
            $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            /*
                 * Etape 3 : @todo : Afficher les mots clés en s'inspirant de ce qui a été fait dans news.php
                 * Attention à ne pas oublier de modifier tag_id=321 avec l'id du mot dans le lien
                 */
            while ($tag = $lesInformations->fetch_assoc()) {
                // echo "<pre>" . print_r($tag, 1) . "</pre>";
            ?>
                <article>
                    <h3>#<?php echo $tag['label']; ?></h3>
                    <p><?php echo $tag['id']; ?></p>
                    <nav>
                        <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">Messages</a>
                    </nav>
                </article>
            <?php } ?>
        </aside>
        <main>
            <h2>Utilisatrices</h2>
            <?php
            /*
                 * Etape 4 : trouver tous les mots clés
                 * PS: on note que la connexion $mysqli à la base a été faite, pas besoin de la refaire.
                 */
            $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            /*
                 * Etape 5 : @todo : Afficher les utilisatrices en s'inspirant de ce qui a été fait dans news.php
                 * Attention à en pas oublier de modifier dans le lien les "user_id=123" avec l'id de l'utilisatrice
                 */
            while ($tag = $lesInformations->fetch_assoc()) {
                echo "<pre>" . print_r($tag, 1) . "</pre>";
            ?>
                <article>
                    <h3><?php echo $tag['alias']; ?></h3>
                    <p><?php echo $tag['id']; ?></p>
                    <nav>
                        <a href="wall.php?user_id=<?php echo $tag['id'] ?>">Mur</a>
                        <a href="feed.php?user_id=<?php echo $tag['id'] ?>">Flux</a>
                        <a href="settings.php?user_id=<?php echo $tag['id'] ?>">Paramètres</a>
                        <a href="followers.php?user_id=<?php echo $tag['id'] ?>">Suiveurs</a>
                        <a href="subscriptions.php?user_id=<?php echo $tag['id'] ?>">Abonnements</a>
                    </nav>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>