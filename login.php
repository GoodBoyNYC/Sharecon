<html>
    <title>Login</title>
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
            echo "You are already logged in. \n";
            echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.\n";
            header("refresh: 1; index.php");
        } else {
            //if the user have entered both entries in the form, check if they exist in the database
            if (isset($_POST["p_id"]) && isset($_POST["password"])) {
                if ($stmt = $mysqli->prepare("select p_id, password from person where p_id = ? and password = ?")) {
                    $sanPID = sanitizeString($_POST["p_id"]);
                    $sanPASS = sanitizeString($_POST["password"]);
                    $stmt->bind_param("ss", $sanPID, md5($sanPASS));
                    $stmt->execute();
                    $stmt->bind_result($p_id, $password);
                    //if there is a match set session variables and send user to homepage
                    if ($stmt->fetch()) {
                        $_SESSION["p_id"] = $p_id;
                        $_SESSION["password"] = $password;
                        $_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"]; //store clients IP address to help prevent session hijack
                        echo "Login successful. \n";
                        echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.";
                        header("refresh: 1; index.php");
                    }
                    //if no match then tell them to try again
                    else {
                        sleep(2); //pause a bit to help prevent brute force attacks
                        echo "Your p_id or password is incorrect, click <a href=\"login.php\">here</a> to try again.";
                    }
                    $stmt->close();
                    $mysqli->close();
                }
            }
            //if not then display login form
            else {
                echo "Enter your Personal ID and password below: <br /><br />\n";
                echo '<form action="login.php" method="POST">';
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
        ?>
    </body>
</html>