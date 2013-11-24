<html>
    <title>Rate</title>
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
            echo "You are not logged in. \n";
            echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.\n";
            header("refresh: 1; index.php");
        }
        else{
            //Presents all data possible to update
        echo '<form action="rate.php" method="POST">';
        echo 'Content available:';
        echo '<select name="selectedContent">';
        if ($stmt = $mysqli->prepare("Select distinct content.c_id From content Inner Join rate where owner_id = ? and rate>=0")) {
            $stmt->bind_param("s", $_SESSION["p_id"]);
            $stmt->execute();
            $stmt->bind_result($cont);
            while ($stmt->fetch()) {
                echo "<option value=$cont> $cont </option>\n";
            }
            $stmt->close();
        }
        echo '</select>';
        echo "\n";
        echo 'Change Rating?: ';
        echo '<select name="newRating">';
        echo "<option value=0> 0 </option>\n";
        echo "<option value=1> 1 </option>\n";
        echo "<option value=2> 2 </option>\n";
        echo "<option value=-1> -1 </option>\n";
        echo "<option value=-2> -2 </option>\n";

        echo '</select>';

        echo "\n";
        echo '<input type="submit" value="Submit" />';
        echo '</form>';
        echo '<br /><a href="index.php">Go back</a>';
        if ($stmt = $mysqli->prepare("insert into rate (p_id, c_id, rate) values(?, ?, ?) on duplicate key update rate=?")) {
            $stmt->bind_param("siii", $_SESSION["p_id"], $_POST["selectedContent"], $_POST["newRating"], $_POST["newRating"]);
            $stmt->execute();
            $stmt->close();
        }
        $mysqli->close();
        }
        
        ?>
    </body>
</html>