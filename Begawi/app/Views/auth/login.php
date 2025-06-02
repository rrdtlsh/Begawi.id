<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna</title>
</head>
<body>

    <h2>Login</h2>

    <form action="/login" method="POST">
        <div>
            <label for="identifier">Email:</label><br>
            <input type="text" id="email" name="email" required placeholder="Masukkan email">
        </div>
        <br>
        <div>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required placeholder="Masukkan password">
        </div>
        <br>
        <div>
            <button type="submit" value="Login">Login</button>
        </div>
    </form>

</body>
</html>