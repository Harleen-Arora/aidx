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
    
    // Replace with your OpenAI API key
    $api_key = "sk-your-openai-api-key-here";
    
    $system_prompt = "You are AID-X Assistant, a helpful AI for a humanitarian aid platform. You help users with aid requests, donations, emergency assistance, NGO partnerships, and volunteer coordination. Always be compassionate and helpful. Respond in " . ($language === 'hi' ? 'Hindi' : 'English') . ". Keep responses under 100 words.";
    
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $message]
        ],
        'max_tokens' => 150,
        'temperature' => 0.7
    ];
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key
            ],
            'content' => json_encode($data)
        ]
    ]);
    
    $response = file_get_contents('https://api.openai.com/v1/chat/completions', false, $context);
    
    if ($response === FALSE) {
        echo json_encode(['response' => 'I am currently unavailable. Please try again later or contact support for immediate assistance.']);
    } else {
        $result = json_decode($response, true);
        if (isset($result['choices'][0]['message']['content'])) {
            echo json_encode(['response' => $result['choices'][0]['message']['content']]);
        } else {
            echo json_encode(['response' => 'I am here to help with humanitarian aid questions. How can I assist you today?']);
        }
    }
} else {
    echo json_encode(['error' => 'Method not allowed']);
}
?>