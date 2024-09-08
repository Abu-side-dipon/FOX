<?php
// File where user balances are stored
$balanceFilePath = 'user_balances.txt';

// Function to load user balances from the file
function loadUserBalances() {
    global $balanceFilePath;
    if (!file_exists($balanceFilePath)) {
        file_put_contents($balanceFilePath, '');
    }
    $balances = file($balanceFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $users = [];
    foreach ($balances as $entry) {
        list($userId, $balance) = explode(':', $entry);
        $users[$userId] = intval($balance);
    }
    arsort($users); // Sort users by balance in descending order
    return array_slice($users, 0, 20); // Return top 20 users
}

// Load user balances
$topUsers = loadUserBalances();

// Ensure the 'TG_ID' and 'UNIQ' parameters are present in the query string
if (isset($_GET['TG_ID']) && isset($_GET['UNIQ'])) {
    $userId = htmlspecialchars($_GET['TG_ID']); // Sanitize input
    $uniqueId = htmlspecialchars($_GET['UNIQ']); // Sanitize input
    $referralLink = "https://t.me/Real_FoxHouse_Bot?start=$userId";
} else {
    // If parameters are missing, redirect to error page or handle accordingly
    header('Location: error.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral & Leaderboard</title>
    <style>
        body {
            background-color: #0d1b2a; /* Dark blue background */
            color: #fff;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .back-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            cursor: pointer;
        }
        .back-icon img {
            width: 65px;
            height: 65px;
        }
        .header img.logo {
            width: 100px;
        }
        .referral-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .referral-link {
            background-color: #1b263b;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 10px;
            font-size: 18px;
        }
        .copy-button {
            padding: 10px 20px;
            background-color: #ff6f61;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .leaderboard {
            margin-top: 30px;
        }
        .leaderboard h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .leaderboard-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #243447;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.1);
        }
        .leaderboard-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .leaderboard-item span {
            flex-grow: 1;
        }
        .footer {
            background-color: #1b263b;
            padding: 10px 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: space-around;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
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
            width: 24px;
            height: 24px;
            background: url('icons/home.svg') no-repeat center center;
            background-size: contain;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .footer .button.task::before {
            content: '';
            display: inline-block;
            width: 24px;
            height: 24px;
            background: url('icons/task.svg') no-repeat center center;
            background-size: contain;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .footer .button.refer::before {
            content: '';
            display: inline-block;
            width: 24px;
            height: 24px;
            background: url('icons/refer.webp') no-repeat center center;
            background-size: contain;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .footer .button div {
            margin-top: 30px;
        }
    </style>
    <script>
        function goBack() {
            window.history.back();
        }

        function copyReferralLink() {
            const referralLink = document.getElementById("referralLink").textContent;
            navigator.clipboard.writeText(referralLink).then(() => {
                alert("Referral link copied to clipboard!");
            }).catch(err => {
                alert("Failed to copy: " + err);
            });
        }
    </script>
</head>
<body>
    <div class="content">
        <div class="header">
            <div class="back-icon" onclick="goBack()">
                <img src="icons/fox-back.webp" alt="Back">
            </div>
            <img class="logo" src="icons/foxy.webp" alt="Fox Logo">
        </div>
        <div class="referral-section">
            <div class="referral-link" id="referralLink"><?php echo htmlspecialchars($referralLink); ?></div>
            <button class="copy-button" onclick="copyReferralLink()">Copy Link</button>
        </div>
        <div class="leaderboard">
            <h2>Leaderboard - Top 20 Users</h2>
            <?php foreach ($topUsers as $userId => $balance): ?>
                <div class="leaderboard-item">
                    <img src='icons/foxy.webp' alt='leaderboard-item'>
                    <span><?php echo htmlspecialchars($userId); ?></span>
                    <span><?php echo htmlspecialchars($balance); ?> coins</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
</body>
</html>
