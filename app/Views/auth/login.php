<!-- login.php -->
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Login</h1>
        <?php if (session()->get('error')) : ?>
            <div class="alert alert-danger"><?= session()->get('error') ?></div>
        <?php endif; ?>
        <form action="/login/postLogin" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="/forget-password" class="btn btn-link">Forgot Password?</a>
        </form>
    </div>
</body>
</html>
