<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no"/>
        <title>Estatística - Coronavírus em PE</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container" style="border-radius: 5px; width: 40%; max-width: 400px">
            <form method="POST" action="login-verification.php">
                <div align="center" class="form-group">
                    <img style="border-radius: 50px; margin-top: 20px" class="mb-4" src="assets/images/logo/pernambuco.svg.png" alt="" width="72" height="72">
                </div>
                <h1 class="h3 mb-3 font-weight-normal">Faça seu login</h1>
                <div class="form-group">
                    <label for="login" class="sr-only">Login</label>
                    <input type="text" name="login" id="login" class="form-control" placeholder="login" required="" autofocus="">
                </div>
                <div class="form-group">
                    <label for="senha" class="sr-only">Senha</label>
                    <input type="password" name="senha" id="senha" class="form-control" placeholder="senha" required="">
                </div>
                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Acessar</button>
                </div>
                <p class="mt-1 mb-3 text-muted">© 2020</p>
            </form>
        </div>
    </body>
</html>
