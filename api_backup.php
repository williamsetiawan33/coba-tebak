<?php

// algoritma random id

// $n = 10;
// $charArray = ["0","1","2","3","4","5", "6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
// $randomPlayerId = "";

// for($i = 0; $i < $n; $i++) {
// $index = rand(0, count($charArray) - 1);
// $randomPlayerId .= $charArray[$index];
// }
// echo $randomPlayerId;



//Access-Control-Allow-Origin header with wildcard.
header('Access-Control-Allow-Origin: *');
// php response type
header('Content-Type: application/json; charset=utf-8');

// api mode selection
$mode = "dev";

if ($mode == "dev") {
    // echo "<script>console.log('developer mode active')</script>";
    // set to developer db
    $conn = mysqli_connect("localhost", "root", "", "coba_tebak");
} else if ($mode == "release") {
    // set to release db
    $conn = mysqli_connect("localhost", "id20849162_cobatebakapi", "Cobatebak+1", "id20849162_coba_tebak");
}

$status = "";

// if request is send
if (isset($_GET["req"])) {
    // if a request is only question
    if ($_GET["req"] == "question") {
        if ($_GET["question"] == "all") {
            $query = mysqli_query($conn, "SELECT * FROM questions");
            $result = [];
            while ($row = mysqli_fetch_assoc($query)) {
                $result[] = $row;
            }
            mysqli_close($conn);

            $result = json_encode($result);


            echo ($result);
        } else {
            $query = mysqli_query($conn, 'SELECT * FROM questions WHERE questionId = ' . '"' . $_GET["question"] . '"');
            $result = [];
            $row = mysqli_fetch_assoc($query);
            $result[] = $row;
            mysqli_close($conn);

            if ($result[0] == null) {
                echo "no data with the given questionId";
            } else {
                $result = json_encode($result);
                echo ($result);
            }
        }
    }
    // if a request is a specific question from a level
    else if ($_GET["req"] == "questionLevel") {
        if (isset($_GET["level"]) && is_numeric($_GET["level"])) {
            $query = mysqli_query($conn, "SELECT * FROM questions WHERE level = " . $_GET["level"]);
            $result = [];
            while ($row = mysqli_fetch_assoc($query)) {
                $result[] = $row;
            }
            mysqli_close($conn);

            $result = json_encode($result);

            echo ($result);
        } else {
            echo "wrong question request format";
        }
    }
    // if a request is a specific question that is contributed by a player
    else if ($_GET["req"] == "questionPlayer") {
        if (isset($_GET["player"])) {
            $query = mysqli_query($conn, "SELECT * FROM questions WHERE playerId = " . '"' . $_GET["player"] . '"');
            $result = [];
            while ($row = mysqli_fetch_assoc($query)) {
                $result[] = $row;
            }
            mysqli_close($conn);

            $result = json_encode($result);


            echo ($result);
        } else {
            echo "wrong question request format";
        }
    } else if ($_GET["req"] == "playerInfo") {
        if (isset($_GET["player"]) && is_numeric($_GET["player"])) {
            $query = mysqli_query($conn, "SELECT playerId, playerName,levelOneScore, levelTwoScore, levelThreeScore FROM players WHERE playerId = " . $_GET["player"]);
            $result = [];
            $row = mysqli_fetch_assoc($query);
            $result[] = $row;
            mysqli_close($conn);

            if ($result[0] == null) {
                echo "no data with the given playerId";
            } else {
                $result = json_encode($result);


                echo ($result);
            }
        } else {
            echo "wrong question request format";
        }
    }
    // if a request is a rank for the main menu, per part and per level
    else if ($_GET["req"] == "playerRank") {
        if ((isset($_GET["level"]) && is_numeric($_GET["level"])) && isset($_GET["listPoss"]) && is_numeric($_GET["listPoss"])) {
            if ($_GET["level"] == 1) {
                $query = mysqli_query($conn, "SELECT playerName, levelOneScore FROM players WHERE levelOneScore != 0 ORDER BY levelOneScore DESC " . "LIMIT " . $_GET["listPoss"] - 1 . ", " . "50");
            } else if ($_GET["level"] == 2) {
                $query = mysqli_query($conn, "SELECT playerName, levelOneScore FROM players WHERE levelTwoScore != 0 ORDER BY levelTwoScore DESC");
            } else if ($_GET["level"] == 3) {
                $query = mysqli_query($conn, "SELECT playerName, levelOneScore FROM players WHERE levelThreeScore != 0 ORDER BY levelThreeScore DESC");
            }
            $result = [];
            while ($row = mysqli_fetch_assoc($query)) {
                $result[] = $row;
            }
            mysqli_close($conn);

            if ($result[0] == null) {
                echo "no data with the given playerId";
            } else {
                $result = json_encode($result);


                echo ($result);
            }
        } else {
            echo "wrong question request format";
        }
    } else {
        echo "wrong question request format";
    }
} else {
    echo "<p>welcome to cobak tebak API. Please refer to documentation for information about how to use this api.</p>";
}

if (isset($_POST['upload'])) {
    if ($_POST["soal"] != "" && $_POST["jawaban"] != "" && $_POST["hint"] != "") {
        mysqli_query($conn, 'INSERT INTO soal values' . '(' . 0 . ',' . '"' . $_POST["soal"] . '"' . ',' . '"' . $_POST["jawaban"] . '"' . ',' . '"' . $_POST["hint"] . '"' . ',' . '"Tim pengembang"' . ')');
        echo "succes";
    }
}
