<html>
    <title>Home</title>
    <body bgcolor="F9BB00">
    <ul id="navigation">
        <li><a href="index.php">Home</a></li>
        <li><a href="view.php">Available Content</a></li>
        <li><a href="rate.php">Rate</a></li>
        <li><a href="post.php">Post</a></li>
        <li><a href="topic.php">Topics</a></li>
        <li><a href="circle.php">Circle</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="sign_up.php">Sign-Up</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
        <?php
        require_once 'needed.php';

        if (!isset($_SESSION["p_id"])) {
            echo 'You may only view public content <a href="view.php">here</a>.
      To post content please <a href="login.php">login</a> or <a href="signup.php">signup</a>.';
            echo "\n";
        } else {
            $p_id = htmlspecialchars($_SESSION["p_id"]);
            echo "Welcome $p_id.<br /><br />\n";
            echo 'You may view the publicly accesible content listed below, <a href="view.php?p_id=';
            echo htmlspecialchars($_SESSION["p_id"]);
            echo '">view your own content</a>, or <a href="post.php">post new content</a>.';
            echo "\n";
        }
        echo "<br /><br />\n";

//if ($stmt = $mysqli->prepare("select p_id from person order by p_id" )) {
//  $stmt->execute();
//  $stmt->bind_result($p_id);
//  while ($stmt->fetch()) {
//    echo '<a href="view.php?p_id=';
//	echo htmlspecialchars($p_id);
//	$p_id = htmlspecialchars($p_id);
//	echo "\">$p_id's blog</a><br />\n";
//  }
//  $stmt->close();
//  $mysqli->close();
//}
        ?>
    </body>
</html>