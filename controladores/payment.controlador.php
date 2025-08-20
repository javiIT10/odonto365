<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit();
}

$accessToken = 'TEST-5101394009384264-081701-f5e5e3cce2deb8578a34fb932948c7c5-382735087';

$paymentData = [
    'transaction_amount' => floatval($input['total']),
    'token' => $input['formData']['token'],
    'description' => 'Cita médica - ' . $input['especialidad'],
    'installments' => intval($input['formData']['installments']),
    'payment_method_id' => $input['formData']['payment_method_id'],
    'issuer_id' => $input['formData']['issuer_id'] ?? null,
    'payer' => [
        'email' => $input['formData']['payer']['email'],
        'identification' => [
            'type' => $input['formData']['payer']['identification']['type'],
            'number' => $input['formData']['payer']['identification']['number']
        ]
    ],
    'external_reference' => $input['citaId'],
    'metadata' => [
        'cita_id' => $input['citaId'],
        'especialista' => $input['especialista'],
        'especialidad' => $input['especialidad'],
        'fecha' => $input['fecha'],
        'hora' => $input['hora']
    ]
];

// Filtrar valores null
$paymentData = array_filter($paymentData, function($value) {
    return $value !== null;
});

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json',
    'X-Idempotency-Key: ' . uniqid()
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    $paymentResponse = json_decode($response, true);
    echo json_encode([
        'status' => $paymentResponse['status'],
        'id' => $paymentResponse['id'],
        'status_detail' => $paymentResponse['status_detail']
    ]);
} else {
    http_response_code($httpCode);
    echo json_encode(['error' => 'Payment failed', 'details' => json_decode($response, true)]);
}
?>