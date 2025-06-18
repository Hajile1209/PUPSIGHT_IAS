<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="aloginstyle.css">
</head>

<body onload="generateCaptcha()">
  <form action="aloginverify.php" method="POST" onsubmit="return validateCaptchaAndOTP();">
    <div class="container-box">
      <h3>User Login</h3>
      
      <input type="text" name="username" id="username" placeholder="Enter Username" required><br>
      <input type="password" name="password" id="password" placeholder="Enter Password" required><br>

      <div class="captcha-wrapper">
        <span id="captchaText"></span>
        <button type="button" onclick="generateCaptcha()">↻</button>
      </div>
      <input type="text" id="captchaInput" placeholder="Enter Captcha" required><br>

      <input type="text" id="otpInput" name="otp" placeholder="Enter OTP" required><br>
      
      <button type="button" onclick="sendOTP()">Send OTP</button>
      <button type="submit">Proceed</button>
    </div>
  </form>

<script>
  let currentCaptcha = '';
  let isCooldown = false;
  let generatedOtp = '';

  function generateCaptcha() {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  currentCaptcha = '';
  for (let i = 0; i < 6; i++) {
    currentCaptcha += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  document.getElementById('captchaText').textContent = currentCaptcha;
}

function sendOTP() {
  if (isCooldown) return;

  const captchaInput = document.getElementById('captchaInput').value;
  const sendOtpBtn = document.querySelector("button[onclick='sendOTP()']");
  const username = document.getElementById('username').value;

  if (!username) {
    alert("Please enter your username before sending OTP.");
    return;
  }

  if (captchaInput === currentCaptcha) {
    generatedOtp = Math.floor(100000 + Math.random() * 900000).toString();
    alert("Your OTP is: " + generatedOtp);
    document.getElementById("otpInput").value = generatedOtp;

    // Send OTP to backend via fetch
    fetch('save_otp.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `username=${encodeURIComponent(username)}&otp=${encodeURIComponent(generatedOtp)}`
    });

    // Start cooldown
    sendOtpBtn.disabled = true;
    sendOtpBtn.textContent = "Wait 10s";
    isCooldown = true;

    let countdown = 10;
    const interval = setInterval(() => {
      countdown--;
      sendOtpBtn.textContent = `Wait ${countdown}s`;
      if (countdown <= 0) {
        clearInterval(interval);
        sendOtpBtn.disabled = false;
        sendOtpBtn.textContent = "Send OTP";
        isCooldown = false;
      }
    }, 1000);
  } else {
    alert("Incorrect CAPTCHA. Please try again.");
    generateCaptcha();
  }
}
    function clearFields() {
      document.getElementById("username").value = '';
      document.getElementById("password").value = '';
      document.getElementById("captchaInput").value = '';
      document.getElementById("otpInput").value = '';
      generateCaptcha();
    }
  </script>
</body>
</html>
