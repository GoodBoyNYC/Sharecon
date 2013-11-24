<html>
    <title>Topic</title>
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

    <form action="topicChosen.php" method="POST">
        <select name="topic">

            <?php
            require_once 'needed.php';

            //logged in

            if (isset($_SESSION["p_id"])) {
                    if ($stmt = $mysqli->prepare("Select Distinct topic from topic natural join content where owner_id = ? or privacy = 0") or die($mysqli->error)){
                    $stmt->bind_param("s", $_SESSION["p_id"]);
                    $stmt->execute();
                    $stmt->bind_result($var1);
                    echo $var1;
                    while ($stmt->fetch() ) {
                        echo "<option value=$var1> $var1 </option>\n";
                    }
                    $stmt->close();
                    $mysqli->close();
                }
            } else {
                if ($stmt = $mysqli->prepare("Select Distinct topic from topic natural join content where privacy = 0")) {
                    $stmt->execute() ;
                    $stmt->bind_result($var1) ;
                    echo $var1;
                    while ($stmt->fetch() ) {
                        echo "<option value=$var1> $var1 </option>\n";
                        }
                    $stmt->close();
                    $mysqli->close();
                }
            }
            ?>
        </select><input type="submit" value="submit">
    </form>
    </body>
</html>
