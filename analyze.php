<?php

if (isset($_POST['analyze'])) {
    if (getimagesize($_FILES['image']['tmp_name']) == 0) {
        echo "<h1>Not an image file</h1>";
        header("Refresh:3; url=index.php");
        exit();
    }

    $target_dir = "images/";
    $file_type = $_FILES['image']['type'];
    $target_file = $target_dir . basename($_FILES['image']['name']);

    if ($file_type == "image/jpeg") {
        $img = imagecreatefromjpeg($_FILES['image']['tmp_name']);
    } elseif ($file_type == "image/png") {
        $img = imagecreatefrompng($_FILES['image']['tmp_name']);
    } elseif ($file_type == "image/bmp") {
        $img = imagecreatefrombmp($_FILES['image']['tmp_name']);
    } elseif ($file_type == "image/webp") {
        $img = imagecreatefromwebp($_FILES['image']['tmp_name']);
    } else {
        echo "<h1>Not an image file</h1>";
        header("Refresh:3; url=index.php");
        exit();
    }


    $image_size = getimagesize($_FILES['image']['tmp_name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    $width = $image_size[0];
    $height = $image_size[1];
    $pixels = array();

    for ($i = 0; $i < $width; $i++) {
        for ($j = 0; $j < $height; $j++) {
            $color = imagecolorsforindex($img, imagecolorat($img, $i, $j));
            $rgb = "R" . $color['red'] . " G" . $color['green'] . " B" . $color['blue'];
            if (isset($pixels[$rgb])) {
                $pixels[$rgb]++;
            } else {
                $pixels[$rgb] = 1;
            }
        }
    }
    arsort($pixels);
    $top_five = array_slice($pixels, 0, 5);
} else {
    header("Location:index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <title>Image color analyzer</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-weight: 600;
        }

        div {
            width: 35%;
        }

        #container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }

        img {
            margin-bottom: 25px;
        }
    </style>
</head>

<body>
    <div id="container">
        <?php
        echo "<img src=" . $target_file . ">";
        foreach ($top_five as $code => $value) {
            $split_code = explode(" ", $code);
            $red = $split_code[0];
            $green = $split_code[1];
            $blue = $split_code[2];
            $code_percent = $value / ($width * $height) * 100;
            echo "<div style=background-color:rgb(" . substr($red, 1)  . "," . substr($green, 1) . "," . substr($blue, 1) . ")" . "> 
        $red, $green, $blue:" . round($code_percent, 2) . "%
        </div>";
        }
        ?>
    </div>
</body>

</html>