<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/modules/ModuleLogin/css/style.css">
    <title>Login</title>
</head>

<body>
    <form action="/login" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">

        <label for="password">Password:</label>
        <input type="password" name="password" id="password">

        <button type="submit">Login</button>
    </form>
</body>

</html>