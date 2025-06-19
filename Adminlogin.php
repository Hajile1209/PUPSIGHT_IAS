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
      <input type="password" name="password_hash" id="password" placeholder="Enter Password" required><br>

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

  <!-- Generate Captcha and OTP -->
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

      // Send OTP via fetch to backend
      fetch('save_otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `username=${encodeURIComponent(username)}&otp=${encodeURIComponent(generatedOtp)}`
      }).then(response => response.text())
        .then(text => console.log(text))
        .catch(error => console.error("OTP Save Error:", error));

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

  function validateCaptchaAndOTP() {
    const captchaInput = document.getElementById('captchaInput').value;
    const otpInput = document.getElementById('otpInput').value;

    if (captchaInput !== currentCaptcha) {
      alert("Incorrect CAPTCHA.");
      return false;
    }

    if (otpInput !== generatedOtp) {
      alert("Incorrect OTP. Make sure to click Send OTP and use the latest code.");
      return false;
    }

    return true;
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
