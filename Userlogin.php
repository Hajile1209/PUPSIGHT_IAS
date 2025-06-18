<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="uloginstyle.css">
</head>

<body onload="generateCaptcha()">

  <div class="container-box">
    <h3>User Login</h3>
    
    <input type="text" name="email" placeholder="Enter Email" required><br>
    <input type="text" name="fullname" placeholder="Enter Full Name (LN, FN, MI)" required><br>

    <div class="captcha-wrapper">
      <span id="captchaText"></span>
      <button type="button" onclick="generateCaptcha()">↻</button>
    </div>
    <input type="text" id="captchaInput" placeholder="Enter Captcha" required><br>

    <input type="text" id="otpInput" placeholder="Enter OTP" required><br>
    
    <input type="text" id="otpInput" name="otp" placeholder="Enter OTP" required><br>
  <button type="submit">Proceed</button>
  </div>

  <!-- Captcha and OTP -->
  <script>
    let currentCaptcha = '';
    let isCooldown = false;

    function generateCaptcha() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    currentCaptcha = '';
    for (let i = 0; i < 6; i++) {
    currentCaptcha += chars.charAt(Math.floor(Math.random() * chars.length));
    }
        document.getElementById('captchaText').textContent = currentCaptcha;
    }

    function sendOTP() {
    if (isCooldown) return; // Block if still on cooldown

    const captchaInput = document.getElementById('captchaInput').value;
    const sendOtpBtn = document.querySelector("button[onclick='sendOTP()']");

    if (captchaInput === currentCaptcha) {
        const otp = Math.floor(100000 + Math.random() * 900000);
        alert("Your OTP is: " + otp);
        document.getElementById('otpInput').value = otp;

    // Disable button
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
  </script>

</body>
</html>
