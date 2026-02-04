<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/modules/ModuleLogin/css/style.css">
    <title>Register</title>
</head>

<body>
    <form action="/register" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">

        <label for="password">Password:</label>
        <input type="password" name="password" id="password">

        <button type="submit">Register</button>
    </form>
</body>

</html>