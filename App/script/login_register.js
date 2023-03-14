const inputs = document.querySelectorAll(".input-field");
const toggle_btn = document.querySelectorAll(".toggle");
const main = document.querySelector("main");
const bullets = document.querySelectorAll(".bullets span");
const images = document.querySelectorAll(".image");
const registerPassword = document.getElementById("clientRegisterPassword");
const confirmPassword = document.getElementById("confirmClientRegisterPassword");
const confirmPasswordErrorIcon = document.getElementById("confirmPasswordErrorIcon");
const registerButton = document.getElementById("registerButton");

window.onload = autoMoveSlider();


// Moving label above the text - field
inputs.forEach((inp) => {
  inp.addEventListener("focus", () => {
    inp.classList.add("active");
  });
  inp.addEventListener("blur", () => {
    if (inp.value != "") return;
    inp.classList.remove("active");
  });
});


// Move label to top if field has a value already
inputs.forEach((inp) => {
  if (inp.value != "") {
    inp.classList.add("active");
  } else {
    inp.classList.remove("active");
  }
});


//  Switching between sign-up, forgot-password and log-in pages
toggle_btn.forEach((btn) => {
  btn.addEventListener("click", () => {
    main.classList = btn.dataset.btnType
  });
});


// Move image slider manual => depends on bullets
function moveSlider() {
  console.log("Value of this in manual: " + this);
  let index = this.dataset.value;

  let currentImage = document.querySelector(`.img-${index}`);
  images.forEach((img) => img.classList.remove("show"));
  currentImage.classList.add("show");

  const textSlider = document.querySelector(".text-group");
  textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

  bullets.forEach((bull) => bull.classList.remove("active"));
  this.classList.add("active");
}

bullets.forEach((bullet) => {
  bullet.addEventListener("click", moveSlider);
});


// Move image slider auto
function autoMoveSlider() {
  let index;

  bullets.forEach((bullet) => {
    if (bullet.classList.contains('active')) {
      index = parseInt(bullet.dataset.value);
    }
  });

  setInterval(() => {
    let currentImage = document.querySelector(`.img-${index}`);
    images.forEach((img) => img.classList.remove("show"));
    currentImage.classList.add("show");

    const textSlider = document.querySelector(".text-group");
    textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

    bullets.forEach((bull) => bull.classList.remove("active"));
    bullets.forEach((bull) => {
      if (bull.dataset.value == index) {
        bull.classList.add("active");
      }
    });

    index += 1;
    if (index > 3)
      index = 1;
  }, 5000);

}


// Allow only numbers in phone and pin input-fields
function onlyNumberKey(evt) {
  var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
    return false;
  return true;
}


// Hide or Unhide eye icon based on password field value
function isPasswordFieldEmpty(inputID, eyeID) {
  var passwordField = document.getElementById(inputID);
  var showPasswordIcon = document.getElementById(eyeID);

  if (passwordField.value == '') {
    showPasswordIcon.hidden = true;
  } else {
    showPasswordIcon.hidden = false;
  }
}


// Display password strength inside password field
function checkPasswordStrength(inputID, eyeIconID) {
  var strongRegex = new RegExp("^(?=.{14,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
  var mediumRegex = new RegExp("^(?=.{10,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
  var enoughRegex = new RegExp("(?=.{8,}).*", "g");
  var passwordStrengthLabel = document.getElementById("passwordStrengthLabel");
  var passwordStrengthInfo = "";

  if (confirmPassword.value != '') {
    confirmEnteredPassword();
  }
  isPasswordFieldEmpty(inputID, eyeIconID)
  if (registerPassword.value == '') {
    passwordStrengthInfo = "";
    registerPassword.style.borderBottomColor = "";
  } else {
    if (enoughRegex.test(registerPassword.value) == false) {
      passwordStrengthInfo = "More Characters";
      registerPassword.style.borderBottomColor = "red";
    } else {
      registerPassword.style.borderBottomColor = "#151111";
      if (strongRegex.test(registerPassword.value)) {
        passwordStrengthInfo = "Strong";
      } else if (mediumRegex.test(registerPassword.value)) {
        passwordStrengthInfo = "Medium";
      } else {
        passwordStrengthInfo = "Weak";
      }
    }
  }
  passwordStrengthLabel.textContent = passwordStrengthInfo;
}


// Display password identity status
function confirmEnteredPassword() {
  if (confirmPassword.value == '') {
    confirmPassword.style.borderBottomColor = "";
    confirmPasswordErrorIcon.style.visibility = "hidden";
  } else {
    if (registerPassword.value != confirmPassword.value) {
      confirmPasswordErrorIcon.style.visibility = "visible";
      confirmPassword.style.borderBottomColor = "red";
      registerButton.disabled = true;
    } else {
      confirmPasswordErrorIcon.style.visibility = "hidden";
      confirmPassword.style.borderBottomColor = "#151111";
      registerButton.disabled = false;
    }
  }
}


// Display password and change status of eye icon 
function showPassword(eyeIconID) {
  const togglePassword = document.getElementById(eyeIconID);

  const toggleButtonClass = togglePassword.className === "uil uil-eye-slash" ? "uil uil-eye" : "uil uil-eye-slash";
  togglePassword.setAttribute('class', toggleButtonClass);

  if (togglePassword.id == "togglePasswordLogin") {
    const loginPasswordField = document.getElementById("clientLoginPassword");
    const loginpasswordType = loginPasswordField.getAttribute('type') === "password" ? "text" : "password";
    loginPasswordField.setAttribute("type", loginpasswordType);
  } else { // register page
    const registerPasswordField = document.getElementById("clientRegisterPassword");
    const registerPasswordType = registerPasswordField.getAttribute('type') === "password" ? "text" : "password";
    registerPasswordField.setAttribute("type", registerPasswordType);

    const confirmClientPasswordField = document.getElementById("confirmClientRegisterPassword");
    const confirmPasswordType = confirmClientPasswordField.getAttribute('type') === "password" ? "text" : "password";
    confirmClientPasswordField.setAttribute("type", confirmPasswordType);
  }
}


// Checks whether user data already exist in database
function clientDataExist(dataName, spanID) {
  jQuery.ajax({
    url: "./backend/check_data_existence.php",
    data: dataName + '=' + $("#" + dataName).val(),
    type: "POST",
    success: function (data) {
      $("#" + spanID).html(data);
    },
    error: function () { }
  });
}

