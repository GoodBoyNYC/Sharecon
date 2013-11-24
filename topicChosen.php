<html>
    <title>Topic Chosen</title>
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
    $passedVar = $_POST['topic'];
    if (isset($_SESSION["p_id"])) {
        if ($stmt = $mysqli->prepare("Select c_id, topic, c_text, privacy, owner_id, rate from topic natural join content natural join rate where owner_id = ?  and topic = ?")) {
            $stmt->bind_param("ss", $_SESSION["p_id"], sanitizeString($passedVar));
            $stmt->execute();
            $stmt->bind_result($c_id, $topic, $c_text, $privacy, $owner_id, $rate);
            while ($stmt->fetch()) {
                echo<<<_END
            <table border="3" width="40%"><tr><td>
            Content ID: $c_id <br>
            Topic     : $topic <br>
            Text      : $c_text <br>
            Privacy   : $privacy <br>
            Owner ID  : $owner_id <br>
            Rate      : $rate <br>
                        </td>
                        </tr>
_END;
            }
            $stmt->close();
            $mysqli->close();
        }
    } else {
        if ($stmt = $mysqli->prepare("Select distinct c_id, topic, c_text, privacy, owner_id from topic natural join content natural join rate where privacy = 0 and topic = ?")) {
            $stmt->bind_param("s", sanitizeString($passedVar));
            $stmt->execute();
            $stmt->bind_result($c_id, $topic, $c_text, $privacy, $owner_id);
            while ($stmt->fetch()) {
                echo<<<_END
            <table border="3" width="40%"><tr><td>
            Content ID: $c_id <br>
            Topic     : $topic <br>
            Text      : $c_text <br>
            Privacy   : $privacy <br>
            Owner ID  : $owner_id <br>
                        </td>
                        </tr>
_END;
            }
            $stmt->close();
            $mysqli->close();
        }
    }
    ?>
    </body>
</html>
