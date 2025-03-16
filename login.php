<?php
session_start(); 

if (isset($_SESSION['user_name'])) {
    header('Location: qrp.php');
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $rfid_code = $input['rfid_code'] ?? '';

    if (!$rfid_code) {
        echo json_encode(['success' => false, 'message' => 'Brak kodu RFID.']);
        exit();
    }

    require_once 'db_connection.php';

    try {
        $stmt = $conn->prepare("SELECT * FROM qrp_users WHERE qrp_users_rfid_code = ?");
        if (!$stmt) {
            throw new Exception("Błąd w przygotowaniu zapytania SQL.");
        }

        $stmt->bind_param("s", $rfid_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $_SESSION['user_name'] = $user['qrp_users_name'];
            $_SESSION['last_activity'] = time();

            echo json_encode(['success' => true, 'user' => $user]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Karta nieznaleziona.']);
            exit();
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Błąd w login.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Wystąpił błąd podczas logowania. Spróbuj ponownie później.']);
        exit();
    } finally {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRP Logowanie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #3498dbb0;
            color: white;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .rfid-container {
            width: 350px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .login-header .rfid-logo {
            width: 80px;
            height: 80px;
            background-image: url('logo2.png');
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
        }

        .login-header .title {
            font-size: 35px;
            font-weight: bold;
            font-family: 'Arial Black', sans-serif;
            margin: 30px;
            color: white;
        }

        label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }

        #rfidInput {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            text-align: center;
        }

        .status-message {
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
        }

        .status-message.success {
            color: green;
        }

        .status-message.error {
            color: red;
        }

        .status-message.waiting {
            color: orange;
        }

        .status-message.registration {
            color: green;
        }

        .registration-form {
            display: none;
            margin-top: 20px;
        }

        .registration-form input {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .registration-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .registration-form button:hover {
            background-color: #45a049;
        }

        a.logo-link {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        a.logo-link img {
            height: 60px;
        }

        .login-image {
            width: 150px;
            height: 120px;
            border: 2px solid white;
            border-radius: 15px;
            margin-right: 10px;
            background-image: url('loginimg2.jpeg');
            background-size: cover;
        }
    </style>
</head>
<body>
    <a href="https://webplatform.canpack.ad/" class="logo-link">
        <img src="logo2.png" alt="Logo">
    </a>

    <div class="rfid-container">
        <div class="login-header">
            <div class="title">QRP</div>
            <div class="login-image"></div>
        </div>

        <label for="rfidInput">Przyłóż kartę RFID:</label>
        <input 
            type="password" 
            id="rfidInput" 
            placeholder="Czekam na kartę RFID..." 
            autocomplete="off" 
            readonly>
        <div id="statusMessage" class="status-message waiting"></div>
        <div id="rfidCodeDisplay" class="status-message"></div>

        <div class="registration-form" id="registrationForm">
            <input type="text" id="userName" placeholder="Wpisz swój numer kontrolera" style="text-transform: uppercase;">
            <button id="registerButton">Zarejestruj</button>
        </div>

    </div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const rfidInput = document.getElementById("rfidInput");
        const statusMessage = document.getElementById("statusMessage");
        const rfidCodeDisplay = document.getElementById("rfidCodeDisplay");
        const registrationForm = document.getElementById("registrationForm");
        const userNameInput = document.getElementById("userName");
        const registerButton = document.getElementById("registerButton");
        let rfidBuffer = "";
        let currentRFID = null;
        let timer;

        function setStatusMessage(message, type) {
            statusMessage.textContent = message;
            statusMessage.className = `status-message ${type}`;
        }

        rfidInput.focus();
        document.addEventListener("click", () => {
            if (document.activeElement !== rfidInput) {
                rfidInput.focus();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (!isNaN(e.key) && e.key.length === 1) {
                rfidBuffer += e.key;

                clearTimeout(timer);

                if (rfidBuffer.length === 6) {
                    currentRFID = rfidBuffer;
                    rfidBuffer = "";
                    processRFID(currentRFID);
                }

                timer = setTimeout(() => {
                    rfidBuffer = "";
                    setStatusMessage("Czekam na kartę RFID...", "waiting");
                }, 2000);
            }
        });

        async function processRFID(code) {
                setStatusMessage("Sprawdzanie karty...", "waiting");
                try {
                    const response = await fetch("", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ rfid_code: code })
                    });
                    const data = await response.json();

                    if (data.success) {
                        setStatusMessage("Logowanie prawidłowe!", "success");
                        rfidCodeDisplay.textContent = `Kod RFID: ${code}`;
                        setTimeout(() => {
                            window.location.href = "qrp.php";
                        }, 2000);
                } else {
                    setStatusMessage("Nie odnajduję numeru Twojej karty. Zarejestruj się proszę.", "error");
                    rfidCodeDisplay.textContent = `Kod RFID: ${code}`;
                    registrationForm.style.display = "block";
                    rfidInput.style.display = "none";
                    userNameInput.focus();
                }
            } catch (error) {
                setStatusMessage("Błąd połączenia z serwerem.", "error");
            }
        }

        registerButton.addEventListener("click", async () => {
            const name = userNameInput.value.trim();
            if (!name) {
                setStatusMessage("Wpisz swoje imię!", "error");
                return;
            }

            try {
                const response = await fetch("/qrp/register_user.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ rfid_code: currentRFID, name })
                });
                const data = await response.json();

                if (data.success) {
                    setStatusMessage("Rejestracja zakończona pomyślnie!", "success");
                    registrationForm.style.display = "none";
                    setTimeout(() => {
                        window.location.href = "https://webplatform.canpack.ad/qrp/qrp.php";
                    }, 2000);
                } else {
                    setStatusMessage("Nie udało się zarejestrować. Spróbuj ponownie.", "error");
                }
            } catch (error) {
                setStatusMessage("Błąd połączenia z serwerem.", "error");
            }
        });
    });
</script>
</body>
</html>
