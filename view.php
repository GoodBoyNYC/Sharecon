<html>
    <title>View</title>
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
        if (isset($_SESSION["p_id"])) {
            echo "Content you can see:";
            if ($stmt = $mysqli->prepare("Select distinct content.c_id, c_text, owner_id, rate From content Inner Join rate where owner_id = ? and rate>=0")) {
                $stmt->bind_param("s", $_SESSION["p_id"]);
                $stmt->execute();
                $stmt->bind_result($cont, $ctext, $ownID, $rate);
                while ($stmt->fetch()) {
                    echo"<table border=\"1\" width=\"40%\"><tr><td>";
                    echo'<form action="view.php" method="POST">';
                    echo"Content ID: $cont <br>";
                    echo"Text      : $ctext <br>";
                    echo"Owner ID  : $ownID <br>";
                    echo"Current Rating: $rate <br>";
                    echo"</td>";
                    echo"</tr>";
                    echo '</form>';
                }
                $stmt->close();
            }
        } else {
            echo "Publicly Avialable Content";

//Outputs public content
            if ($stmt = $mysqli->prepare("select distinct content.c_id, c_text, owner_id from content where privacy=0")) {
                $stmt->execute();
                $stmt->bind_result($cont, $ctext, $ownID);
                while ($stmt->fetch()) {
                    echo"<table border=\"1\" width=\"40%\"><tr><td>";
                    echo"Content ID: $cont <br>";
                    echo"Text      : $ctext <br>";
                    echo"Owner ID  : $ownID <br>";
                    
                    echo"</td>";
                    echo"</tr>";
                    echo"<br>";
                    echo"<br>";
                }
            }

//Allows user to view own content

            $stmt->close();
            
        }
        echo"<br>Average Rating of content: <br>";
        if ($stmt = $mysqli->prepare("SELECT c_id, AVG(rate) FROM rate natural join content WHERE owner_id = ? or privacy = 0 GROUP BY c_id")) {
            $stmt->bind_param("s", $_SESSION["p_id"]);
            $stmt->execute();
            $stmt->bind_result($cont, $rate);
            while ($stmt->fetch()) {
                echo"<table border=\"1\" width=\"40%\"><tr><td>";
                echo'<form action="view.php" method="POST">';
                echo"Content ID: $cont <br>";
                echo"Current Rating: $rate <br>";
                echo"</td>";
                echo"</tr>";
                echo '</form>';
            }
            $stmt->close();
        }
        $mysqli->close();
        ?>
    </body>
</html>