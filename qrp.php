<?php 
$allowedIPs = ['10.11.x.x', '10.11.x.x', '10.11.x.x'];
$userIP = $_SERVER['REMOTE_ADDR'];
if (!in_array($userIP, $allowedIPs)) {
    header('Location: https://webplatform.canpack.ad/qrp/search.php');
    exit();
}

session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QRP</title>
<style>
.user-info {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    align-items: center;
    font-family: Arial, sans-serif;
}

.user-info img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
    cursor: pointer;
}

.user-info span {
    font-size: 16px;
    color: #cecaca;
}

.logout-btn {
    display: none;
}

.user-info {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    align-items: center;
    font-family: Arial, sans-serif;
}

.user-info .user-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #0171B9;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    margin-right: 10px;
    margin-top: -4px;
}

.user-info #user-initial {
    font-size: 18px;
    text-transform: uppercase;
}

.user-menu {
    display: none;
    position: absolute;
    top: 50px;
    right: 0;
    background-color: white;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 10px;
    width: 200px;
    font-size: 14px;
    color: #444;
}

.user-menu p {
    margin: 0;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

.user-menu a {
    color: #0171B9;
    text-decoration: none;
    font-weight: normal;
    transition: color 0.3s ease;
}

.user-menu a:hover {
    color: #0056b3;
}

.user-info span {
    font-size: 16px;
    color: #cecaca;
}
</style>
</head>
<body>
<div class="user-info">
    <div class="user-icon" id="user-icon" onclick="toggleMenu()">
        <span id="user-initial"></span>
    </div>
    <span id="user-name">Witaj, <?php echo htmlspecialchars($user_name); ?></span>
    <div id="user-menu" class="user-menu">
        <p>Witaj, <?php echo htmlspecialchars($user_name); ?>!</p>
        <a href="logout.php" id="logout-btn">Wyloguj</a>
    </div>
</div>
<a href="logout.php" id="logout-btn" class="logout-btn">Wyloguj</a>

<script>
function logout() {
    window.location.href = 'logout.php';
}
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QRP</title>
<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #c6cacd;
}

.navbar {
    width: 100%;
    background-color: #333;
    color: white;
    height: 50px;
    display: flex;
    align-items: center;
}

.navbar .logo img {
    margin-right: 10px;
    max-height: 40px;
    margin-left: 10px;
    margin-top: 4px;
}

.navbar nav a {
    color: #fff;
    font-size: 14px;
    text-decoration: none;
    margin: 0 10px;
    padding: 8px 16px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.navbar nav a:hover {
    background-color: #555;
}

.lowbar {
    width: 100%;
    background-color: #0171B9;
    color: white;
    height: 50px;
    display: flex;
    align-items: center;
}

.lowbar .title {
    font-size: 20px;
    font-weight: bold;
    margin-left: 10px;
}

.container {
    width: 100%;
    max-width: 1800px;
    margin: 20px auto;
    display: flex;
    gap: 20px;
}

.left-section,
.right-section,
.center-section {
    background-color: #F0F8FF;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.left-section,
.right-section {
    flex: 3;
}

.center-section {
    flex: 10;
    text-align: center;
}

.camera-stream {
    background-color: #F0F8FF;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.camera-stream img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    background-color: black;
}

.form-group {
    margin-bottom: 20px;
}

.form-group textarea {
    width: 90%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    resize: vertical;
    font-size: 14px;
}

.form-group select {
    width: 90%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    background-color: #fff;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    box-sizing: border-box;
}

.form-group select:focus {
    border-color: #007bff;
    outline: none;
}

.form-group button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}

.form-group button:hover {
    background-color: #0056b3;
}

.error-message {
    color: red;
    font-size: 12px;
    margin-top: 5px;
}

.last-record img {
    width: 100%;
    height: auto;
    border-radius: 6px;
}

@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }
    .left-section,
    .right-section,
    .center-section {
        flex: unset;
    }
}

label,
a {
    display: inline-block;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #444;
    text-decoration: none;
    transition: color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

label:hover,
a:hover {
    color: #0171B9;
    transform: scale(1.05);
    text-decoration: underline;
}

label.error,
a.error {
    color: #d9534f;
    font-weight: normal;
}

@media (max-width: 768px),
(orientation: portrait) {
    .container {
        flex-direction: column;
        gap: 10px;
    }
    .form-group select {
        width: 40%;
    }
    .form-group textarea {
        width: 38%;
    }
    .form-group button {
        width: 40%;
    }
    .left-section,
    .center-section,
    .right-section {
        flex: unset;
        width: 90%;
        margin-bottom: 10px;
        margin-left: auto;
        margin-right: auto;
    }
    .last-record img {
        width: 60%;
        height: auto;
        border-radius: 6px;
    }
    .center-section img {
        max-width: 80%;
        height: auto;
    }
}

select,
textarea,
button {
    font-size: 18px;
    padding: 12px;
    border-radius: 8px;
    width: 100%;
    box-sizing: border-box;
}

select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: #f0f0f0 url('arrow-down.png') no-repeat scroll right center;
    background-size: 20px;
}

input[type="file"] {
    display: none;
}

input[type="submit"] {
    background-color: #0171B9;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-size: 18px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0059a0;
}

</style>
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>
    <nav>
        <a href="index.php">Start</a>
        <a href="test-list.php">Lista testów</a>
    </nav>
</div>
<div class="lowbar">
    <div class="title">Rejestracja testu</div>
</div>
<div class="container">
    <div class="left-section">
        <div class="camera-stream">
            <div class="form-group">
                <label for="camera-stream">Podgląd z kamery</label>
                <div id="camera-stream">
                    <img src="camera-feed.jpg" alt="Camera Preview">
                </div>
            </div>
        </div>
    </div>
    <div class="center-section">
        <form id="registration-form">
            <div class="form-group">
                <label for="line-select">Linia produkcyjna:</label>
                <select id="line-select" name="line" required>
                    <option value="">Wybierz linię</option>
                    <option value="line1">Linia 1</option>
                    <option value="line2">Linia 2</option>
                    <option value="line3">Linia 3</option>
                </select>
            </div>
            <div class="form-group">
                <label for="test-type">Typ testu:</label>
                <select id="test-type" name="test_type" required>
                    <option value="">Wybierz typ testu</option>
                    <option value="type1">Test 1</option>
                    <option value="type2">Test 2</option>
                    <option value="type3">Test 3</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Opis testu:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" id="register-btn">Zarejestruj test</button>
            </div>
        </form>
    </div>
    <div class="right-section">
        <div class="last-record">
            <h3>Ostatnia rejestracja</h3>
            <div id="last-record">
                <img src="last-image.jpg" alt="Last Recorded Image">
                <p>Typ testu: Test 1</p>
                <p>Linia: Linia 1</p>
            </div>
        </div>
    </div>
</div>
<script>
<script>
const measurement_types = {
    1: 'Pokrycie lakierem górnej części pobocznicy - CuSO4', 
    2: 'Pokrycie denka roztwór - CuSO4', 
    3: 'Ilość oleju na przewężeniu (talk kosmetyczny)', 
    4: 'Pokrycie kołnierza lakierem - CuSO4', 
    5: 'Pokrycie denka roztwór - CuSO4, Pokrycie kołnierza lakierem - CuSO4, Ilość oleju na przewężeniu (talk kosmetyczny)'
};

function clearForm() {
    document.getElementById('line').value = '';
    document.getElementById('type').value = '';
    document.getElementById('description').value = '';
    document.getElementById('orderNumber').style.display = 'none';
    document.getElementById('orderDisplay').innerText = '';
    document.getElementById('testTypeNumber').style.display = 'none';
    document.getElementById('testTypeDisplay').innerText = '';
}

function handleLineChange(event) {
    const line = event.target.value;
    if (line) {
        fetch(`get_qrp_order.php?line=${line}`).then(response => {
            if (!response.ok) throw new Error('Błąd w komunikacji z serwerem.');
            return response.json();
        }).then(data => {
            if (data.success) {
                document.getElementById('orderDisplay').innerText = data.orderNumber;
                document.getElementById('orderNumber').style.display = 'block';
            } else {
                throw new Error('Nie udało się pobrać numeru zlecenia.');
            }
        }).catch(error => {
            console.error('Błąd:', error.message);
            document.getElementById('orderNumber').style.display = 'none';
        });
    } else {
        document.getElementById('orderNumber').style.display = 'none';
    }
}

function handleRegisterClick() {
    const line = document.getElementById('line').value;
    const description = document.getElementById('description').value.trim();
    const type = document.getElementById('type').value;
    if (!line) {
        alert('Proszę wybrać linię!');
        return;
    }
    if (!type) {
        alert('Proszę wybrać typ testu!');
        return;
    }
    const formData = new FormData();
    formData.append('line', line);
    formData.append('description', description);
    formData.append('type', type);
    fetch('qrp_fetch.php', {
        method: 'POST', 
        body: formData,
    }).then(response => {
        if (!response.ok) throw new Error('Błąd podczas zapisu danych.');
        return response.json();
    }).then(data => {
        if (data.success) {
            alert('Dane zapisane pomyślnie!');
            getLastRecord();
            clearForm();
        } else {
            throw new Error(data.message || 'Nieoczekiwany błąd serwera.');
        }
    }).catch(error => {
        console.error('Błąd:', error.message);
        alert('Błąd: ' + error.message);
    });
}

function getLastRecord() {
    fetch('get_last_record.php').then(response => {
        if (!response.ok) throw new Error('Błąd w komunikacji z serwerem.');
        return response.json();
    }).then(data => {
        if (data.success) {
            updateLastRecordUI(data);
        } else {
            throw new Error('Nie udało się pobrać ostatniego zapisu.');
        }
    }).catch(error => {
        console.error('Błąd:', error.message);
        clearLastRecordUI();
    });
}

function updateLastRecordUI(data) {
    document.getElementById('lastImage').src = data.filePath || '#';
    document.getElementById('lastImage').alt = data.filePath ? 'Ostatnie zdjęcie' : 'Brak zdjęcia';
    document.getElementById('lastLine').innerText = data.line || 'Brak linii';
    document.getElementById('lastOrder').innerText = data.orderNumber || 'Brak numeru zlecenia';
    const testTypeNumber = parseInt(data.type, 10);
    let testTypeName = measurement_types[testTypeNumber] || 'Brak rodzaju testu';
    if (testTypeName.includes(',')) {
        testTypeName = testTypeName.replace(/,/g, '<br>');
    }
    document.getElementById('lastTestType').innerHTML = testTypeName;
    document.getElementById('lastDescription').innerText = data.description || 'Brak komentarza';
    document.getElementById('lastDate').innerText = data.entryDate || 'Brak daty';
    document.getElementById('lastUser').innerText = data.userName || 'Brak użytkownika';
}

function clearLastRecordUI() {
    document.getElementById('lastImage').src = '#';
    document.getElementById('lastImage').alt = 'Brak zdjęcia';
    document.getElementById('lastLine').innerText = 'Brak linii';
    document.getElementById('lastOrder').innerText = 'Brak numeru zlecenia';
    document.getElementById('lastTestType').innerText = 'Brak rodzaju testu';
    document.getElementById('lastDate').innerText = 'Brak daty';
    document.getElementById('lastDescription').innerText = 'Brak komentarza';
    document.getElementById('lastUser').innerText = 'Brak użytkownika';
}

document.getElementById('line').addEventListener('change', handleLineChange);
document.getElementById('registerButton').addEventListener('click', handleRegisterClick);
getLastRecord();

function toggleMenu() {
    const menu = document.getElementById('user-menu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

const userName = "<?php echo htmlspecialchars($user_name); ?>";
const initials = userName.split(' ').map(word => word.charAt(0).toUpperCase()).join('');
document.getElementById('user-initial').innerText = initials;

const userIp = "<?php echo $user_ip; ?>";
const targetIp = "10.11.x.x";
if (userIp === targetIp) {
    let inactivityTimeout;
    const inactivityLimit = 5 * 60 * 1000;
    function autoLogout() {
        window.location.href = 'logout.php';
    }
    function resetInactivityTimer() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(autoLogout, inactivityLimit);
    }
    window.onload = resetInactivityTimer;
    window.onmousemove = resetInactivityTimer;
    window.onkeydown = resetInactivityTimer;
    window.onclick = resetInactivityTimer;
}

function handleTestTypeChange(event) {
    const type = event.target.value;
    const testTypeDisplay = document.getElementById('testTypeDisplay');
    const testTypeNumber = document.getElementById('testTypeNumber');
    if (type) {
        const selectedOption = event.target.options[event.target.selectedIndex];
        testTypeDisplay.innerText = selectedOption.text;
        testTypeNumber.style.display = 'block';
    } else {
        testTypeNumber.style.display = 'none';
    }
}

document.getElementById('type').addEventListener('change', handleTestTypeChange);

function updateCameraStream() {
    fetch('get_camera_image.php').then(response => {
        if (!response.ok) throw new Error('Błąd podczas pobierania obrazu kamery.');
        return response.blob();
    }).then(blob => {
        const imageUrl = URL.createObjectURL(blob);
        const cameraStream = document.getElementById('cameraStream');
        cameraStream.src = imageUrl;
    }).catch(error => {
        console.error('Błąd:', error.message);
    });
}

setInterval(updateCameraStream, 1000);
updateCameraStream();
</script>

</body>
</html>
