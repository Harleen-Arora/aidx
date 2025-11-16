<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT fullname, phone, aidtype, details, type, latitude, longitude, created_at FROM aid_requests ORDER BY created_at DESC");
        $stmt->execute();
        $requests = $stmt->fetchAll();
        
        // Add default coordinates and emergency level for display
        foreach ($requests as &$request) {
            if (!$request['latitude'] || !$request['longitude']) {
                // Default coordinates for major cities based on aid type
                $coords = [
                    'food' => [28.6139, 77.2090], // Delhi
                    'medical' => [19.0760, 72.8777], // Mumbai
                    'shelter' => [12.9716, 77.5946], // Bangalore
                ];
                $request['latitude'] = $coords[$request['aidtype']][0] ?? 28.6139;
                $request['longitude'] = $coords[$request['aidtype']][1] ?? 77.2090;
            }
            $request['emergency_level'] = 'Medium';
            $request['address'] = 'Location provided';
        }
        
        echo json_encode($requests);
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    echo json_encode([]);
}
?>