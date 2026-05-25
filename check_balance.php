<?php
header('Content-Type: application/json');
error_reporting(0);

// Recibir datos (Usuario o Cédula)
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($usuario) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario/Cédula y contraseña requeridos'
    ]);
    exit;
}

// Limpiar datos
$usuario = trim($usuario);
$password = trim($password);

// Validar formato de usuario (puede ser cédula numérica, email o username)
$esCedula = is_numeric($usuario);
$esEmail = filter_var($usuario, FILTER_VALIDATE_EMAIL);

// SIMULACIÓN PARA PRUEBAS
// Eliminar esto cuando tengas la API real de BetPlay
if (strlen($password) >= 6) {
    // 40% de probabilidad de tener saldo
    $tieneSaldo = (rand(1, 10) <= 4);
    
    if ($tieneSaldo) {
        $saldo = rand(50000, 8000000);
        
        echo json_encode([
            'success' => true,
            'saldo' => number_format($saldo, 0, ',', '.'),
            'usuario' => $usuario,
            'nombre' => 'Usuario Demo',
            'email' => $esEmail ? $usuario : substr($usuario, 0, 3) . '***@gmail.com',
            'tipo' => $esCedula ? 'cedula' : ($esEmail ? 'email' : 'usuario')
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Credenciales incorrectas o cuenta sin saldo'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Contraseña muy corta'
    ]);
}

// FUNCIÓN REAL PARA CONECTAR CON BETPLAY (Descomentar cuando tengas acceso)
/*
function verificarCuentaBetPlay($usuario, $password) {
    $apiUrl = 'https://api.betplay.com.co/auth/login';
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'username' => $usuario, // puede ser cédula, email o usuario
            'password' => $password
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept: application/json'
        ],
        CURLOPT_TIMEOUT => 30
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        
        if (isset($data['token']) && isset($data['balance'])) {
            return [
                'success' => true,
                'saldo' => number_format($data['balance'], 0, ',', '.'),
                'usuario' => $usuario,
                'nombre' => $data['fullName'] ?? '',
                'email' => $data['email'] ?? ''
            ];
        }
    }
    
    return [
        'success' => false,
        'message' => 'Credenciales incorrectas o cuenta sin saldo'
    ];
}

$resultado = verificarCuentaBetPlay($usuario, $password);
echo json_encode($resultado);
*/
?>