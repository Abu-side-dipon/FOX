<?php
// Path to the directory where user data is stored
$userDataDir = 'Users/';

// Ensure the directory exists
if (!is_dir($userDataDir)) {
    mkdir($userDataDir, 0777, true);
}

// Function to save user data
function saveUserData($userId, $uniqueId) {
    global $userDataDir;
    $filePath = $userDataDir . $userId . '.json';

    $data = [
        'user_id' => $userId,
        'unique_id' => $uniqueId,
    ];

    file_put_contents($filePath, json_encode($data));
}

// Check if the POST request contains user data
if (isset($_POST['userId']) && isset($_POST['uniqueId'])) {
    $userId = htmlspecialchars($_POST['userId']);
    $uniqueId = htmlspecialchars($_POST['uniqueId']);

    saveUserData($userId, $uniqueId);
    echo 'User data saved successfully.';
} else {
    echo 'Invalid data.';
}
?>
