<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Arca</title>
<link rel="icon" type="image/png" sizes="32x32" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

</head>
<body>
<div class="glow-orb glow-orb-1"></div>
<div class="glow-orb glow-orb-2"></div>
<div class="glow-orb glow-orb-3"></div>
<header>
    <div class="left">
        <div class="logo"><img src="../assets/img/arca.png" alt="logo"></div>
        <nav>
            <a href="../index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </nav>
    </div>
</header>

<div class="main-login-wrapper">
    <div class="bubbles-login">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>
    <div class="container-login">
    <div class="intro">
        <div class="intro-eyebrow">Inventory System</div>
        <h1>Welcome <span>Back!</span></h1>
        <p>Enter your account details below to access your inventory dashboard.</p>
    </div>
    <div class="form-box">
        <form action="../../server/auth/login.php" method="post" autocomplete="off">
            <input type="text" name="email" placeholder="Email" required>
            <div class="password-wrapper">
                <input type="password" name="password" placeholder="Password" id="password" required>
                <span class="toggle-pass" data-target="password">show</span>
            </div>
            <button type="submit" name="login">Login</button>
            <p class="signup-link">Don't have an account? <a href="register.php">Register here</a></p>
        </form>
        <p id="error" class="error-msg" style="display: none;"></p>
    </div>
    </div>
</div>

<script>

  const urlParams = new URLSearchParams(window.location.search);
  const msg = urlParams.get("msg");
  if(msg){
      const error = document.getElementById("error");
      if(error){
          error.textContent = msg;
          error.style.display = "block";

     
          setTimeout(() => {
              error.style.display = "none";
          }, 5000);
      }

      
      window.history.replaceState({}, document.title, window.location.pathname);
  }

 
  document.querySelectorAll(".toggle-pass").forEach(toggle => {
      toggle.addEventListener("click", () => {
          const input = document.getElementById(toggle.dataset.target);
          if(input.type === "password"){
              input.type = "text";
              toggle.textContent = "hide";
              toggle.classList.add("active"); 
          } else {
              input.type = "password";
              toggle.textContent = "show";
              toggle.classList.remove("active"); 
          }
      });
      document.addEventListener("DOMContentLoaded", function() {
    const email = document.querySelector('input[name="email"]');
    const username = document.querySelector('input[name="username"]');
    const password = document.getElementById("password");
    const confirm = document.getElementById("confirm");
    const form = document.getElementById("signupform");

    const emailError = document.getElementById("email-error");
    const passwordError = document.getElementById("password-error");
    let hideTimeout;


    document.querySelectorAll(".toggle-pass").forEach(toggle => {
        toggle.addEventListener("click", () => {
            const input = document.getElementById(toggle.dataset.target);
            if (!input) return;
            if(input.type === "password"){
                input.type = "text";
                toggle.textContent = "hide";
                toggle.classList.add("active");
            } else {
                input.type = "password";
                toggle.textContent = "show";
                toggle.classList.remove("active");
            }
        });
    });

    function isStrongPassword(pass) {
        return /[A-Z]/.test(pass) && /[a-z]/.test(pass) && /[0-9]/.test(pass);
    }


    function isValidEmail(emailVal) {
        return /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|outlook|hotmail)\.[a-z]{2,}$/.test(emailVal);
    }


    function validateEmail() {
        const emailVal = email.value.trim();
        if (!emailVal) { 
            emailError.style.display = "none";
            return false;
        }
        if (!isValidEmail(emailVal)) {
            emailError.textContent = "Email must be valid (Gmail, Yahoo, Outlook, Hotmail)";
            emailError.style.display = "block";
            return false;
        }
        emailError.style.display = "none";
        return true;
    }


    function validatePasswords() {
        const passVal = password.value.trim();
        const confirmVal = confirm.value.trim();

        if (passVal.length < 8 || !isStrongPassword(passVal)) {
            passwordError.textContent = "Password must be 8 characters and contain uppercase, lowercase, and number";
            passwordError.style.display = "block";
            clearTimeout(hideTimeout);
            hideTimeout = setTimeout(() => {
                passwordError.style.display = "none";
            }, 5000);
            return false;
        }

        if (passVal !== confirmVal) {
            passwordError.textContent = "Passwords do not match";
            passwordError.style.display = "block";
            return false;
        }

        passwordError.style.display = "none";
        return true;
    }


    email.addEventListener("input", validateEmail);
    password.addEventListener("input", validatePasswords);
    confirm.addEventListener("input", validatePasswords);


    form.addEventListener("submit", (event) => {
        const emailValid = validateEmail();
        const passValid = validatePasswords();
        if (!emailValid || !passValid) {
            event.preventDefault(); 
        }
    });


    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get("msg");
    if (msg) {
        passwordError.textContent = msg;
        passwordError.style.display = "block";
    }
});

  });
</script>

<script>
    function switchToSignup() {
    document.querySelector('.bubbles').classList.remove('login');
    document.querySelector('.bubbles').classList.add('signup');

}

function switchToLogin() {
    document.querySelector('.bubbles').classList.remove('signup');
    document.querySelector('.bubbles').classList.add('login');
}

</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const email = document.querySelector('input[name="email"]');
    const username = document.querySelector('input[name="username"]');
    const password = document.getElementById("password");
    const confirm = document.getElementById("confirm");
    const form = document.getElementById("signupform");

    const emailError = document.getElementById("email-error");
    const passwordError = document.getElementById("password-error");
    let hideTimeout;


    document.querySelectorAll(".toggle-pass").forEach(toggle => {
        toggle.addEventListener("click", () => {
            const input = document.getElementById(toggle.dataset.target);
            if (!input) return;
            if(input.type === "password"){
                input.type = "text";
                toggle.textContent = "hide";
                toggle.classList.add("active");
            } else {
                input.type = "password";
                toggle.textContent = "show";
                toggle.classList.remove("active");
            }
        });
    });

    function isStrongPassword(pass) {
        return /[A-Z]/.test(pass) && /[a-z]/.test(pass) && /[0-9]/.test(pass);
    }


    function isValidEmail(emailVal) {
        return /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|outlook|hotmail)\.[a-z]{2,}$/.test(emailVal);
    }


    function validateEmail() {
        const emailVal = email.value.trim();
        if (!emailVal) { 
            emailError.style.display = "none";
            return false;
        }
        if (!isValidEmail(emailVal)) {
            emailError.textContent = "Email must be valid (Gmail, Yahoo, Outlook, Hotmail)";
            emailError.style.display = "block";
            return false;
        }
        emailError.style.display = "none";
        return true;
    }


    function validatePasswords() {
        const passVal = password.value.trim();
        const confirmVal = confirm.value.trim();

        if (passVal.length < 8 || !isStrongPassword(passVal)) {
            passwordError.textContent = "Password must be 8 characters and contain uppercase, lowercase, and number";
            passwordError.style.display = "block";
            clearTimeout(hideTimeout);
            hideTimeout = setTimeout(() => {
                passwordError.style.display = "none";
            }, 5000);
            return false;
        }

        if (passVal !== confirmVal) {
            passwordError.textContent = "Passwords do not match";
            passwordError.style.display = "block";
            return false;
        }

        passwordError.style.display = "none";
        return true;
    }


    email.addEventListener("input", validateEmail);
    password.addEventListener("input", validatePasswords);
    confirm.addEventListener("input", validatePasswords);


    form.addEventListener("submit", (event) => {
        const emailValid = validateEmail();
        const passValid = validatePasswords();
        if (!emailValid || !passValid) {
            event.preventDefault(); 
        }
    });


    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get("msg");
    if (msg) {
        passwordError.textContent = msg;
        passwordError.style.display = "block";
    }
});

</script>

</body>
</html>
