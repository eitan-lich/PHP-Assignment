<?php

 //Verifying the user indeed has submitted an image and hasnt typed the URL of this page directly
if (isset($_POST['analyze'])) { 
    /*
    Making sure the image we have been submitted is a valid image file, taken from here https://stackoverflow.com/questions/9314164/php-uploading-files-image-only-checking
    I decided to make use of this function since it is simple and straight forward
    */ 
    if (getimagesize($_FILES['image']['tmp_name']) == 0) {
        echo "<h1>Not an image file</h1>";
        header("Refresh:3; url=index.php");
        exit();
    }

    $target_dir = "images/";
    $file_type = $_FILES['image']['type'];
    $target_file = $target_dir . basename($_FILES['image']['name']);

    /*
    Preparing the path for the temporary image file to be moved to a permanent image file in the /images directory, 
    we need to do this in order to be able to later use the real path of the image to display the image 
    */

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

    /*
    Creating a GD object using the appropriate type of image which from my understanding is going to allow us to later use this object in
    order to retrieve the color of a specific pixel in the image
    */


    $image_size = getimagesize($_FILES['image']['tmp_name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    $width = $image_size[0];
    $height = $image_size[1];
    $pixels = array();

    /*
        Obtaining the width and height of the picture since we are going to scan every pixel line by line in the picture and get its color
        we have also moved the file to the path we have previously defined in $target_file and last we have created an array which
        we are going to use as an array of counters to count how many times a specific RGB code happens in the picture
    */

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

    /*
        Scanning every columnm and every row in the picture, with help of the imagecolorsforindex() function we can retreive the color of the
        pixel at the specific X,Y coordinate taken from here https://stackoverflow.com/questions/7727843/detecting-colors-for-an-image-using-php
        We need to use the imagecolorsforindex() function in order to convert the value returned from imagecolorat() to a proper array containing 
        values of the pixel regarding its color.
        The color of the pixel is defined by 4 seperate values, red, green, blue and opacity.
        With use of the array of counters we verify if there has been a previous entry for that RGB code using isset() which will make sure that
        key is defined in the array, if it is than we simply increment its value by 1 meaning that specific RGB code has already been previously seen


    */
    arsort($pixels);
    $top_five = array_slice($pixels, 0, 5);

    /*
            sorting the array of counters based on the value (the amount of times a color appeared in a nutshell) in reverse order (to big the biggest)
            and then slicing the array to only get the top 5 most popular colors
    */
} else {
    header("Location:index.php"); // Redirect incase of isset() returns false meaning the user tried to enter the URL of analyze.php without submitting an image
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
        /*
            We first of all display the image the user has uploaded using the $target_file that contains the path to the file 
            we have previously moved from being a temporary file in the tmp folder.
            After that we iterate over the 5 keys and values in the top 5 most popular colors array,
            we split the array into a string so we can use their value in the background color of the div and also to show the
            user the value of that specific pixel and also in order to calculate the % that color appears in the picture
            the $code_percent is calculated by dividing the amount of times that RGB code has appeared in the picture and dividing it
            by the product of the width*height of the picture and then multiplying by 100 to get the value in percentages


        */
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