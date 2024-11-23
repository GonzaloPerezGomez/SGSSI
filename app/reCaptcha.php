<?php // Verificación de reCAPTCHA

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        echo "<script>alert('verifique que no es usted un robot');</script>";
        echo "<script>window.location.href = '" . htmlspecialchars($_SERVER['PHP_SELF']) . "';</script>";
        exit();
    }

    $captcha = $_POST['g-recaptcha-response'];

    if (!$captcha) {
        echo "<script>alert('Por favor completa el reCAPTCHA.');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    }

    // Verificar el token con la API de Google reCAPTCHA
    $secretKey = getenv('RECAPTCHA_SECRET_KEY'); // O usa tu clave secreta directamente
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $captcha,
        'remoteip' => $_SERVER['REMOTE_ADDR'],
    ];

    // Solicitud a la API de reCAPTCHA
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

    if (!$result['success']) {
        echo "<script>alert('Error de validación de reCAPTCHA.');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    }
} ?>