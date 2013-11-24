<!DOCTYPE html>
<html>
    <title>Post</title>
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
            echo "You're not logged in, can't post! \n";
            echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.\n";
            header("refresh: 1; index.php");
        } else {
            //Make sure no blanks
            if (isset($_POST["circleName"])) {
                if (isset($_POST["description"])) {
                    //creates the new circle
                    if ($stmt = $mysqli->prepare("insert into circle (owner_id, circle_name, description) values (?, ?, ?)")) {
                        $stmt->bind_param("sss", $_SESSION["p_id"], sanitizeString($_POST["circleName"]), sanitizeString($_POST["description"]));
                        $stmt->execute();
                        $stmt->close();
                    }
                    //add owner of circle to be a part of the circle
                    if ($stmt = $mysqli->prepare("insert into incirc (owner_id, circle_name, p_id) values (?, ?, ?)")) {
                        $stmt->bind_param("sss", $_SESSION["p_id"], sanitizeString($_POST["circleName"]), $_SESSION["p_id"]);
                        $stmt->execute();
                        $stmt->close();
                    }
                    echo "Your new circle is created. \n";
                    echo "You will be redirected home in 1 seconds or click <a href=\"index.php\">here</a>.";
                    header("refresh: 1; index.php");
                }
            }
            //Check for blanks
            //OVERHERE BOAISDAODIHSAALKDJHDSAKJASDH
            if (isset($_POST["newPerson"])) {
                //add into circle of choice
                if ($stmt = $mysqli->prepare("insert into incirc (owner_id, circle_name, p_id) values (?, ?, ?)")) {
                    $stmt->bind_param("sss", $_SESSION["p_id"], sanitizeString($_POST["pickC"]), sanitizeString($_POST["newPerson"]));
                    $stmt->execute();
                    $stmt->close();
                }
                echo "New person added to circle. \n";
                echo "You will be redirected home in 1 seconds or click <a href=\"index.php\">here</a>.";
                header("refresh: 1; index.php");
            }



            //if not then display to create circle
            else {
                echo "Enter your circle name: <br /><br />\n";
            }
            echo '<form action="circle.php" method="POST">';
            echo 'Circle: <input type="text" name="circleName" /><br />';
            echo "\n";
            echo 'Description: <input type="text" name="description" /><br />';
            echo "\n";
            echo '<input type="submit" value="Submit" />';
            echo "\n";
            echo '</form>';
            echo "\n";
            echo '<br /><a href="index.php">Go back</a>';

            echo '<form action="circle.php" method="POST">';
            echo 'Person to Add:';
            echo '<select name="newPerson">';
            if ($stmt = $mysqli->prepare("Select p_id from person")) {
                $stmt->execute();
                $stmt->bind_result($var3);
                while ($stmt->fetch()) {
                    echo "<option value=$var3> $var3 </option>\n";
                }
                $stmt->close();
            }
            echo '</select>';
            echo "\n";
            echo 'Circle: ';
            echo '<select name="pickC">';
            if ($stmt = $mysqli->prepare("Select circle_name from circle where owner_id=?")) {
                $stmt->bind_param("s", $_SESSION["p_id"]);
                $stmt->execute();
                $stmt->bind_result($var2);
                while ($stmt->fetch()) {
                    echo "<option value=$var2> $var2 </option>\n";
                }
                $stmt->close();
            }
            echo '</select>';

            echo "\n";
            echo '<input type="submit" value="Submit" />';
            echo "\n";
            echo '</form>';
            echo "\n";
            echo '<br /><a href="index.php">Go back</a>';
            $mysqli->close();
        }
        ?>
    </body>
</html>