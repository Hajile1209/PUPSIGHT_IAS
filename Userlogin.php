<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="uloginstyle.css">
</head>

<body onload="generateCaptcha()">
  <form action="uloginverify.php" method="POST" onsubmit="return validateCaptchaAndOTP();">
    <div class="container-box">
      <h3>User Login</h3>
      
      <input type="text" name="email" id="email" placeholder="Enter Email" required><br>
      <input type="text" name="fullname" id="fullname" placeholder="Enter Full Name (LN, FN, MI)" required><br>

      <div class="captcha-wrapper">
        <span id="captchaText"></span>
        <button type="button" onclick="generateCaptcha()">↻</button>
      </div>
      <input type="text" id="captchaInput" placeholder="Enter the answer" required><br>

      <input type="text" id="otpInput" name="otp" placeholder="Enter OTP" required><br>
      <button type="button" onclick="sendOTP()">Send OTP</button>
      <button type="submit">Proceed</button>
    </div>
  </form>

  <script>
    let correctAnswer = 0;
    let isCooldown = false;
    let generatedOtp = '';
    
    function generateCaptcha() {
      const num1 = Math.floor(Math.random() * 20);
      const num2 = Math.floor(Math.random() * 20);
      correctAnswer = num1 + num2;
      document.getElementById('captchaText').textContent = `${num1} + ${num2} = ?`;
    }

    function sendOTP() {
      if (isCooldown) return;

      const captchaInput = document.getElementById('captchaInput').value;
      const email = document.getElementById('email').value;
      const sendOtpBtn = document.querySelector("button[onclick='sendOTP()']");

      if (!email) {
        alert("Please enter your email first.");
        return;
      }
      // Will display an OTP via alert(script)
      if (parseInt(captchaInput) === correctAnswer) {
        generatedOtp = Math.floor(100000 + Math.random() * 900000).toString();
        alert("Your OTP is: " + generatedOtp);

        // Will Starts an 10-second cooldown on the OTP button. The button will be disabled
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

      if (parseInt(captchaInput) !== correctAnswer) {
        alert("Incorrect CAPTCHA.");
        return false;
      }

      if (!otpInput || otpInput.length < 6) {
        alert("Please enter the OTP sent.");
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
