<?php
// File where user balances are stored
$balanceFilePath = 'user_balances.txt';

// Function to get the user's current balance
function getUserBalance($userId) {
    global $balanceFilePath;
    $balances = file($balanceFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($balances as $balance) {
        list($id, $amount) = explode(':', $balance);
        if ($id == $userId) {
            return (int)$amount;
        }
    }
    return 0; // Return 0 if the user has no balance recorded
}

// Function to update the user's balance
function updateUserBalance($userId, $newBalance) {
    global $balanceFilePath;
    $balances = file($balanceFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updatedBalances = [];
    $userFound = false;
    
    foreach ($balances as $balance) {
        list($id, $amount) = explode(':', $balance);
        if ($id == $userId) {
            $updatedBalances[] = $userId . ':' . $newBalance;
            $userFound = true;
        } else {
            $updatedBalances[] = $balance;
        }
    }

    if (!$userFound) {
        $updatedBalances[] = $userId . ':' . $newBalance;
    }

    file_put_contents($balanceFilePath, implode("\n", $updatedBalances));
}

// Handle task completion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_task'])) {
    $userId = $_POST['user_id'];
    $coinReward = 200; // Reward for completing the task

    // Get current balance and update
    $currentBalance = getUserBalance($userId);
    $newBalance = $currentBalance + $coinReward;
    updateUserBalance($userId, $newBalance);

    // Redirect to the task completion page
    header('Location: complete.php?coins=' . $coinReward);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Tasks</title>
    <style>
        body {
            background-color: #0d1b2a; /* Dark blue background */
            color: #fff;
            margin: 0;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 50px;
            cursor: pointer;
        }
        .task-container {
            background-color: #1b263b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            margin-bottom: 20px;
        }
        .task-container button {
            background-color: transparent;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
        }
        .task-container button img {
            width: 30px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .task-container button.disabled {
            cursor: not-allowed;
            color: #888;
        }
        #countdown {
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
    <script>
        function startTask(taskId) {
            var joinButton = document.getElementById('joinButton' + taskId);
            var countdownElement = document.getElementById('countdown' + taskId);
            var countdown = 30; // 30 seconds countdown

            joinButton.disabled = true;
            joinButton.classList.add('disabled');
            joinButton.innerHTML = "Started";

            var countdownInterval = setInterval(function() {
                countdown--;
                countdownElement.innerHTML = "Waiting for " + countdown + " seconds...";

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    joinButton.disabled = false;
                    joinButton.classList.remove('disabled');
                    joinButton.innerHTML = "Task Complete - Claim 200 🦊";
                    joinButton.onclick = function() {
                        document.getElementById('taskForm' + taskId).submit();
                    };
                }
            }, 1000);
        }

        function goToTelegram(taskId) {
            window.open("https://t.me/CSFTEAM3", "_blank"); // Replace with your Telegram channel link
            startTask(taskId);
        }
    </script>
</head>
<body>
    <div class="header">
        <img src="icons/fox-back.webp" alt="Back Icon" onclick="goBack()">
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

    <!-- Task 1 -->
    <div class="task-container">
        <h2>Join Telegram Channel</h2>
        <p>Click the button below to join our Telegram channel. After joining, wait for 30 seconds to complete the task and earn 200 🦊.</p>
        <form id="taskForm1" method="post">
            <input type="hidden" name="user_id" value="12345"> <!-- Replace with actual user ID -->
            <input type="hidden" name="complete_task" value="1">
            <button type="button" id="joinButton1" onclick="goToTelegram(1);">
                <img src="icons/telegram.webp" alt="Telegram Icon"> Join
            </button>
            <div id="countdown1"></div>
        </form>
    </div>

    <!-- Task 2 -->
    <div class="task-container">
        <h2>Follow Twitter Account</h2>
        <p>Click the button below to follow our Twitter account. After following, wait for 30 seconds to complete the task and earn 200 🦊.</p>
        <form id="taskForm2" method="post">
            <input type="hidden" name="user_id" value="12345"> <!-- Replace with actual user ID -->
            <input type="hidden" name="complete_task" value="1">
            <button type="button" id="joinButton2" onclick="goToTwitter(2);">
                <img src="icons/twitter.webp" alt="Twitter Icon"> Follow
            </button>
            <div id="countdown2"></div>
        </form>
    </div>

    <!-- Task 3 -->
    <div class="task-container">
        <h2>Like Facebook Page</h2>
        <p>Click the button below to like our Facebook page. After liking, wait for 30 seconds to complete the task and earn 200 🦊.</p>
        <form id="taskForm3" method="post">
            <input type="hidden" name="user_id" value="12345"> <!-- Replace with actual user ID -->
            <input type="hidden" name="complete_task" value="1">
            <button type="button" id="joinButton3" onclick="goToFacebook(3);">
                <img src="icons/facebook.webp" alt="Facebook Icon"> Like
            </button>
            <div id="countdown3"></div>
        </form>
    </div>

    <script>
        function goToTwitter(taskId) {
            window.open("https://twitter.com/youraccount", "_blank"); // Replace with your Twitter account link
            startTask(taskId);
        }

        function goToFacebook(taskId) {
            window.open("https://facebook.com/yourpage", "_blank"); // Replace with your Facebook page link
            startTask(taskId);
        }
    </script>
</body>
</html>
