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
        $sanTEXT;

        $currentMax;
//if the user is not logged in, redirect them back to homepage
        if (!isset($_SESSION["p_id"])) {
            echo "You're not logged in, can't post! \n";
            echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.\n";
            header("refresh: 1; index.php");
        } else {
            //gets max content_id
            if ($stmt = $mysqli->prepare("SELECT MAX( c_id ) FROM content")) {
                $stmt->execute();
                $stmt->bind_result($currentMax);
                $stmt->fetch();
                ++$currentMax;
                $stmt->close();
            }


            //Make sure user wrote a post
            if (isset($_POST["ctext"])) {
                //query for a GIVEN topic
                if (isset($_POST["pTopic"])) {
                    //submit into content w/ topic AND A CIRCLE WAS CHOSEN
                    if ($_POST["pickC"] != 0) {
                        if ($stmt = $mysqli->prepare("insert into content (c_id, c_text, privacy, owner_id, p_time) values (?, ?, 1, ?, NOW())")) {
                            $stmt->bind_param("iss", $currentMax, sanitizeString($_POST["ctext"]), $_SESSION["p_id"]);
                            $stmt->execute();
                            $stmt->close();
                        }
                        if (strlen($_POST["pTopic"]) > 0) {
                            if ($stmt = $mysqli->prepare("INSERT INTO topic (c_id, topic) VALUES (?, ?)")) {
                                $stmt->bind_param("is", $currentMax, sanitizeString($_POST["pTopic"]));
                                $stmt->execute();
                                $stmt->close();
                            }
                        }
                        //whom ever owns the circle is allowing others in the circle to view this particular item
                        if ($stmt = $mysqli->prepare("INSERT INTO visible (owner_id, circle_name, c_id) VALUES (?, ?, ?)")) {
                            $stmt->bind_param("ssi", $_SESSION["p_id"], sanitizeString($_POST["pickC"]), $currentMax);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }//IF CIRCLE CHOSEN
                    else {
                        //NONE WAS CHOSEN. USE THE CHOSEN PRIVACY SETTING
                        if ($stmt = $mysqli->prepare("insert into content (c_id, c_text, privacy, owner_id, p_time) values (?, ?, ?, ?, NOW())")) {
                            $stmt->bind_param("isis", $currentMax, sanitizeString($_POST["ctext"]), sanitizeString($_POST["Privacy"]), $_SESSION["p_id"]);
                            $stmt->execute();
                            $stmt->close();
                        }
                        if (strlen($_POST["pTopic"]) > 0) {
                            if ($stmt = $mysqli->prepare("INSERT INTO topic (c_id, topic) VALUES (?, ?)")) {
                                $stmt->bind_param("is", $currentMax, sanitizeString($_POST["pTopic"]));
                                $stmt->execute();
                                $stmt->close();
                            }
                        }
                    }//End of ELSE
                    echo "Your post was successful. \n";
                    echo "You will be redirected home in 2 seconds or click <a href=\"post.php\">here</a>.";
                    header("refresh: 2; post.php");
                }
                $mysqli->close();
            }

            //if not then display the form for posting content
            else {
                echo "Enter your post: <br /><br />\n";
            }
            echo '<form action="post.php" method="POST">';
            echo 'Topic: <input type="text" name="pTopic" /> Privacy:';
            echo '<select name="Privacy">';
            echo "<option value=0> 0 </option>\n";
            echo "<option value=1> 1 </option>\n";
            echo '</select>';
            echo 'Circle: ';
            echo '<select name="pickC">';
            echo "<option value=0>NONE</option>";
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
            echo '</br>';
            echo "\n";
            echo '<textarea cols="40" rows="10" name="ctext" /></textarea><br />';
            echo "\n";
            echo '<input type="submit" value="Submit" />';
            echo "\n";

            echo '</form>';
            echo "\n";

            echo '<br /><a href="index.php">Go back</a>';
        }
        ?>
    </body>
</html>