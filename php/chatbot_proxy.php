<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $message = $input['message'] ?? '';
    $language = $input['language'] ?? 'en';
    
    if (empty($message)) {
        echo json_encode(['error' => 'No message provided']);
        exit;
    }
    
    // Forward request to Python chatbot server
    $data = json_encode([
        'message' => $message,
        'language' => $language
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $data
        ]
    ]);
    
    $response = file_get_contents('http://localhost:5000/chat', false, $context);
    
    if ($response === FALSE) {
        echo json_encode(['error' => 'Chatbot service unavailable']);
    } else {
        echo $response;
    }
} else {
    echo json_encode(['error' => 'Method not allowed']);
}
?>