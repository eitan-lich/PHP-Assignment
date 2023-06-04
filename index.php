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

        /* basic styling for our input form, using flexbox to align items horizontally and vertically */
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Roboto', sans-serif;
        }


        form {
            height: 350px;
            width: 500px;
            border: 1px solid black;
            border-radius: 20px;
            box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);
        }

        #input-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        p {
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- Sending the selected image to our analyze.php file which will handle the logic and display the color information,
        additionally we have to specify the enctype of our form to be multipart/form-data in order to send files using the POST method -->
    <form action="analyze.php" method="post" enctype="multipart/form-data">
        <p>Select an image to analyze its colors</p>
        <div id="input-wrapper">
            <input type="file" name="image" required>
            <input type="submit" value="Analyze" name="analyze">
        </div>
    </form>
</body>

</html>