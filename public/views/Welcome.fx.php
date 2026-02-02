<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./public/css/style.css">
    <title>HomePage</title>
</head>

<body>
    <h2>Welcome to</h2>
    <div class="perspective-box">
        <div class="box">
            <div class="front">
                @component('WelcomeTitle')
            </div>
            <div class="right">
                @component('WelcomeTitle')
            </div>
            <div class="back">
                @component('WelcomeTitle')
            </div>
            <div class="left">
                @component('WelcomeTitle')
            </div>
            <div class="top">
            </div>
            <div class="bottom">
            </div>

            <div class="shadow">
            </div>
        </div>
    </div>
</body>

</html>