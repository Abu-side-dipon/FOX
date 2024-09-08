<?php
// Path to the directory where user JSON files will be stored
$baseDir = __DIR__ . '/Users/';

// Function to generate a random balance between 709 and 803
function generateRandomBalance() {
    return rand(709, 803);
}

// Function to get or create user balance
function getUserBalance($userId) {
    global $baseDir;

    $filePath = $baseDir . $userId . '.json';

    // If the file does not exist, create it with a random balance
    if (!file_exists($filePath)) {
        $initialBalance = generateRandomBalance();
        $data = ['userId' => $userId, 'uniqueId' => '', 'balance' => $initialBalance];
        file_put_contents($filePath, json_encode($data));
        saveBalanceToFile($userId, $initialBalance); // Save balance in txt format
        return $initialBalance;
    }

    // Read the existing balance from the file
    $data = json_decode(file_get_contents($filePath), true);
    return $data['balance'];
}

// Function to update the balance
function updateUserBalance($userId, $newBalance) {
    global $baseDir;

    $filePath = $baseDir . $userId . '.json';

    // Read existing data
    $data = json_decode(file_get_contents($filePath), true);

    // Update the balance
    $data['balance'] = $newBalance;

    // Write the updated data back to the file
    file_put_contents($filePath, json_encode($data));
    
    // Save updated balance to txt format
    saveBalanceToFile($userId, $newBalance);
}

// Function to save balance in text format
function saveBalanceToFile($userId, $balance) {
    $balanceFilePath = __DIR__ . '/user_balances.txt';
    $balanceEntry = " $userId: $balance\n";  // Added a space before $userId
    
    // Append the balance to the user_balances.txt file
    file_put_contents($balanceFilePath, $balanceEntry, FILE_APPEND | LOCK_EX);
}

// Check if the 'TG_ID' and 'UNIQ' parameters are present in the query string
if (isset($_GET['TG_ID']) && isset($_GET['UNIQ'])) {
    $userId = htmlspecialchars($_GET['TG_ID']); // Sanitize input
    $uniqueId = htmlspecialchars($_GET['UNIQ']); // Sanitize input

    // Get or create user balance
    $userBalance = getUserBalance($userId);

    // No need to update the balance if it already exists
    $updatedBalance = $userBalance;
    if (!file_exists($baseDir . $userId . '.json')) {
        $updatedBalance += 10; // Increment balance by 10 on first visit
        updateUserBalance($userId, $updatedBalance);
    }

    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>User Dashboard</title>
        <style>
            body {
                background-color: #0d1b2a; /* Dark blue background */
                color: #fff;
                margin: 0;
                font-family: Arial, sans-serif;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 100vh;
            }
            .balance {
                text-align: center;
                padding: 20px;
                font-size: 24px;
                background-color: #1b263b;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            }
            .fox-image {
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .fox-image img {
                width: 280px;
                height: auto;
            }
            .footer {
                background-color: #1b263b;
                padding: 10px 0;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.5);
            }
            .footer .buttons {
                display: flex;
                justify-content: space-around;
            }
            .footer .button {
                text-align: center;
                flex-grow: 1;
                padding: 10px;
                color: #fff;
                font-size: 18px;
                border-radius: 10px;
                margin: 0 5px;
                background-color: transparent;
                border: 2px solid #fff;
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
                transition: background-color 0.3s, border-color 0.3s;
                cursor: pointer;
                position: relative;
            }
            .footer .button:hover {
                background-color: rgba(255, 255, 255, 0.1);
                border-color: #ff6f61;
            }
            .footer .button.home::before {
                content: '';
                display: inline-block;
                width: 40px;
                height: 40px;
                background: url('icons/home.webp') no-repeat center center;
                background-size: contain;
                position: absolute;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
            }
            .footer .button.task::before {
                content: '';
                display: inline-block;
                width: 40px;
                height: 40px;
                background: url('icons/task.webp') no-repeat center center;
                background-size: contain;
                position: absolute;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
            }
            .footer .button.refer::before {
                content: '';
                display: inline-block;
                width: 40px;
                height: 40px;
                background: url('icons/refer.webp') no-repeat center center;
                background-size: contain;
                position: absolute;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
            }
            .footer .button div {
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <div class='balance'>
            Balance: <strong>$updatedBalance</strong> coins
        </div>
        <div class='fox-image'>
            <img src='icons/foxy.webp' alt='Fox'>
        </div>
        <div class='footer'>
            <div class='buttons'>
                <div class='button home'>
                    <div>Home</div>
                </div>
                <div class='button task' onclick=\"window.location.href='https://csftasin.co/Fox/task.php?TG_ID=$userId&UNIQ=$uniqueId'\">
                    <div>Task</div>
                </div>
                <div class='button refer' onclick=\"window.location.href='https://csftasin.co/Fox/refer.php?TG_ID=$userId&UNIQ=$uniqueId'\">
                    <div>Refer</div>
                </div>
            </div>
        </div>
    </body>
    </html>";
} else {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error</title>
        <style>
            body {
                background-color: #0d1b2a;
                color: #fff;
                margin: 0;
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                text-align: center;
            }
            .container {
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #1b263b;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            }
            h1 {
                color: #ff6f61;
            }
            p {
                font-size: 18px;
                color: #ddd;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Error</h1>
            <p>Invalid or missing parameters. Please use the link provided by the bot.</p>
        </div>
    </body>
    </html>";
}
?>
