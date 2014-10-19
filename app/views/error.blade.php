<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

        * {
            margin: 0;
        }

        html {
            color: #888;
            font-family: sans-serif;
            text-align: center;
        }

        body {
            left: 50%;
            margin: -63px 0 0 -275px;
            position: absolute;
            top: 50%;
            width: 550px;
        }

        p {
            font-size: 26px;
            line-height: 42px;
        }

        @media only screen and (max-width: 270px) {

            body {
                margin: 10px auto;
                position: static;
                width: 95%;
            }
        }

    </style>
</head>
<body>
<p>{{{ $error }}}</p>
</body>
</html>
