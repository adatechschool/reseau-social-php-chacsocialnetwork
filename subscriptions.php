<?php include "connection.php"; ?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Send - Abonnements</title>
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
    <div id="wrapper">
        <aside>
            <img src="user.png" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes dont
                    l'utilisatrice <?php echo $user['alias']; ?> <?php echo intval($_GET['user_id']) ?>suit les messages
                </p>

            </section>
        </aside>
        <main class='contacts'>
            <?php
            // Etape 1: récupérer l'id de l'utilisateur
            $userId = intval($_GET['user_id']);

            // Etape 3: récupérer le nom de l'utilisateur
            $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Etape 4: à vous de jouer
            //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
            while ($follower = $lesInformations->fetch_assoc()) {
                // echo "<pre>" . print_r($follower, 1) . "</pre>";
            ?>
                <article>
                    <img src="user.png" alt="blason" />
                    <h3><a href="wall.php?user_id=<?php echo $follower['id'] ?>"><?php echo $follower['alias']; ?></a></h3>
                    <p>(n° <?php echo $follower["id"] ?>)</p>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>