<html>
    <title>Sign-Up</title>
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
    $sanPID;
    $sanPASS;
    
//if the user is already logged in, redirect them back to homepage
    if (isset($_SESSION["p_id"])) {
        echo "You are already logged in. ";
        echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.";
        header("refresh: 3; index.php");
    } else {
        //if the user have entered _all_ entries in the form, insert into database
        if (isset($_POST["p_id"]) && isset($_POST["password"])) {
            $sanPID= sanitizeString($_POST["p_id"]);
            $sanPASS= sanitizeString($_POST["password"]);

            //check if p_id already exists in database
            if ($stmt = $mysqli->prepare("select p_id from person where p_id = ?")) {
                $stmt->bind_param("s", $sanPID);
                $stmt->execute();
                $stmt->bind_result($p_id);
                if ($stmt->fetch()) {
                    echo "That Personal ID already exists. ";
                    echo "You will be redirected in 3 seconds or click <a href=\"sign_up.php\">here</a>.";
                    header("refresh: 3; sign_up.php");
                    $stmt->close();
                }
                //if not then insert the entry into database, note that user_id is set by auto_increment
                else {
                    $stmt->close();
                    if ($stmt = $mysqli->prepare("insert into person (p_id,password) values (?,?)")) {
                        $stmt->bind_param("ss", $sanPID, md5($sanPASS));
                        $stmt->execute();
                        $stmt->close();
                        echo "Registration complete, click <a href=\"index.php\">here</a> to return to homepage.";
                    }
                }
            }
        }
        //if not then display registration form
        else {
            echo "Enter your information below: <br /><br />\n";
            echo '<form action="sign_up.php" method="POST">';
            echo "\n";
            echo 'Username: <input type="text" name="p_id" /><br />';
            echo "\n";
            echo 'Password: <input type="password" name="password" /><br />';
            echo "\n";
            echo '<input type="submit" value="Submit" />';
            echo "\n";
            echo '</form>';
            echo "\n";
            echo '<br /><a href="index.php">Go back</a>';
        }
    }
    $mysqli->close();
    ?>
    </body>
</html>