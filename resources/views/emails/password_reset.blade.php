<!DOCTYPE html>
<html>
<head>
    <title>Redefinição de Senha</title>
</head>
<body>
    <p>Olá,</p>
    <p>Você solicitou a redefinição de sua senha. Clique no link abaixo para redefinir:</p>
    <p>
        <a href="{{ str_replace(parse_url(url('/'), PHP_URL_HOST), preg_replace('/^[^\.]+\./', '', parse_url(url('/'), PHP_URL_HOST)), url('/reset?token=' . $token)) }}">
            Redefinir minha senha
        </a>
    </p>
    <p>Se você não solicitou essa redefinição, por favor, ignore este e-mail.</p>
    <p>Obrigado,</p>
    <p>Equipe do {{ config('app.name') }}</p>
</body>
</html>
