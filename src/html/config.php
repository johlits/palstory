<?php
$db = mysqli_connect($_ENV["DB_SERVER"], $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"], null);

$dbexists = $db->prepare("SELECT SCHEMA_NAME
FROM INFORMATION_SCHEMA.SCHEMATA
WHERE SCHEMA_NAME = 'story';");
if ($dbexists->execute()) {
    if (mysqli_num_rows($dbexists->get_result()) == 0) {
        $sqlScript = file_get_contents('../story.sql');
        if ($db->multi_query($sqlScript)) {
            $page = $_SERVER['PHP_SELF'];
            $sec = "3";
            header("Refresh: $sec; url=$page");
            echo "Database initialized. Refreshing in 3 seconds..";
        } else {
            echo "Error initializing database: " . $db->error;
        }
    }
    mysqli_select_db($db, "story");
}

?>