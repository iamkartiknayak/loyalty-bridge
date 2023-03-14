const sidebar = document.querySelector("nav");
const sidebarToggle = document.querySelector(".sidebar-toggle");
const navlinks = document.querySelectorAll(".nav-links li");
const selectCategory = document.querySelector("#selectCategory");
const sortCategory = document.querySelector("#sortCategory");
const categoryIcon = document.querySelector("#categoryIcon");
const typeIcon = document.querySelector("#typeIcon");
const claimTypeIcon = document.querySelector("#claimTypeIcon");
const claimType = document.querySelector("#claimType");
const selectType = document.querySelector("#selectType");
const sortType = document.querySelector("#sortType");
const selectClaimOption = document.querySelector("#selectClaimOption");
const categoryOptions = document.querySelectorAll(".categoryOptions");
const typeOptions = document.querySelectorAll(".typeOptions")
const claimOptions = document.querySelectorAll(".claimOptions")
const categoryList = document.querySelector(".categoryList");
const typeList = document.querySelector(".typeList");
const claimOptionsList = document.querySelector(".claimOptionsList")
const inputs = document.querySelectorAll(".input-field");
const sortIcons = document.querySelectorAll(".sort-icon");
const fieldPens = document.querySelectorAll(".field-pen");
const inputFields = document.querySelectorAll(".input-field");

const loyalty_coin_value = document.querySelector(".input-11").value;
const credit_percentage = document.querySelector(".input-12").value;
const debit_percentage = document.querySelector(".input-13").value;

const newPassword = document.querySelector("#accountInfoNewPassword")
const confirmPassword = document.querySelector("#accountInfoConfirmPassword")


window.onload = () => {
    document.cookie = "searchNumber=";
    document.cookie = "sortCategory=transaction_number";
    document.cookie = "sortType=descending";
    document.cookie = "transactionType=credit";
    document.querySelector("#dashboardPage").click();
    updateCustomerTable();
    displaySettingsUpdateStatus();
}

function displaySettingsUpdateStatus() {
    if (getCookie("account_update_status") == "success" || getCookie("business_update_status") == "success") {
        showToast("Changes made successfully");
    } else if (getCookie("business_update_status") == "failed" || getCookie("business_update_status") == "failed") {
        showToast("Something went wrong");
    }

    if (getCookie("account_update_status") == "success" || getCookie("account_update_status") == "failed") {
        document.querySelector("#accountPage").click();
    }

    if (getCookie("business_update_status") == "success" || getCookie("business_update_status") == "failed") {
        document.querySelector("#businessPage").click();
    }

    if (getCookie("invalid_password") == "true") {
        showToast("Invalid password");
    }
}

setInterval(updateCustomerTable, 100);


// Enable to edit the text-field in account page
fieldPens.forEach((fieldPen) => fieldPen.addEventListener("click", function () {
    let pen_index = fieldPen.className.split(" ")[3].split("-")[1];
    inputFields.forEach((inputField) => {
        if (inputField.classList.contains("input-" + pen_index)) {
            inputField.readOnly = false;
            inputField.select();
        }
    })
}));


// Changes the text-field property to readonly when lost focus
inputFields.forEach((inputField) => inputField.addEventListener("blur", () => {
    inputField.readOnly = false;
}))


// Moving label above the text-field
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


// Updates the table using db values
function updateCustomerTable() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        document.querySelector("#customerTable").innerHTML = this.responseText;
    }
    xhttp.open("GET", "./backend/realtime_customer_table.php");
    xhttp.send();
}


// Updates selection of nav-links
navlinks.forEach((navlink) => navlink.addEventListener("click", function () {
    navlinks.forEach((navlink) => navlink.classList.remove("active"));
    this.classList.add("active");

    navlinks.forEach((navlink) => {
        if (navlink && typeof navlink.textContent === 'string') {
            const idValue = navlink.textContent.replace(/[ -/\n]/g, '')?.toLowerCase();
            const isActive = navlink.classList.contains('active');
            const section = document.querySelector(`#${idValue}`);

            if (section) {
                if (isActive) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            }
        }
    });
}));


// Toggles the sidebar to full-view and side-view
sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});

// ! ***************************************************************

// function selectCompanyImage() {
var fileToRead = document.querySelector("#updateImageButton");

fileToRead.addEventListener("change", function (event) {
    // console.log("Filename: " + fileToRead);
    var file = fileToRead.files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        console.log(reader.result);
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
    };
    if (file.length) {
        console.log("Filename: " + file[0].name);
        console.log("Type: " + file[0].type);
        console.log("Size: " + file[0].size + " bytes");
    }

}, false);
// }


// ! ***************************************************************8



function getSortCategoryIcon(category) {
    if (category == "Date") {
        categoryIcon.className = "uil uil-calendar-alt";
        document.cookie = "sortCategory=transaction_number";
    }
    else if (category == "Coins") {
        categoryIcon.className = "uil uil-coins";
        document.cookie = "sortCategory=total_loyalty_points";
    }
    else if (category == "Amount") {
        categoryIcon.className = "uil uil-rupee-sign";
        document.cookie = "sortCategory=purchase_sum";
    }
    categoryList.classList.toggle("active")
    updateCustomerTable();

}


categoryOptions.forEach((option) => option.addEventListener("click", function () {
    sortCategory.innerHTML = this.textContent;
    getSortCategoryIcon(this.textContent.trim());
}
));


function getSortTypeIcon(type) {
    if (type == "Ascending") {
        typeIcon.className = "uil uil-sort-amount-up";
        document.cookie = "sortType=ascending";
    }
    else if (type == "Descending") {
        typeIcon.className = "uil uil-sort-amount-down";
        document.cookie = "sortType=descending";
    }
    typeList.classList.toggle("active")
    updateCustomerTable();
}


typeOptions.forEach((option) => option.addEventListener("click", function () {
    sortType.innerHTML = this.textContent;
    getSortTypeIcon(this.textContent.trim());
}
));


function getClaimOptionIcon(type) {
    if (type == "Gain Coins") {
        claimTypeIcon.className = "uil uil-plus-circle";
        document.cookie = "transactionType=credit";
    }
    else if (type == "Retrieve Coins") {
        claimTypeIcon.className = "uil uil-minus-circle";
        document.cookie = "transactionType=redemption";
    }
    claimOptionsList.classList.toggle("active");
}


claimOptions.forEach((option) => option.addEventListener("click", function () {
    claimType.innerHTML = this.textContent;
    getClaimOptionIcon(this.textContent.trim());
}
));


selectCategory.addEventListener("click", () => {
    categoryList.classList.toggle("active");
    typeList.classList.remove("active");
    claimOptionsList.classList.remove("active");
})


selectType.addEventListener("click", () => {
    typeList.classList.toggle("active");
    categoryList.classList.remove("active");
    claimOptionsList.classList.remove("active");
});


selectClaimOption.addEventListener("click", () => {
    claimOptionsList.classList.toggle("active");
    categoryList.classList.remove("active");
    typeList.classList.remove("active");
});


// Allow only numbers in input-fields
function onlyNumberKey(evt) {
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}


function getNumberInfo() {
    let searchNumber = document.querySelector("#customerNumber").value;
    document.cookie = "searchNumber=" + searchNumber;
    updateCustomerTable();
}


function getCookie(cookieName) {
    let cookie = {};
    document.cookie.split(';').forEach(function (el) {
        let [key, value] = el.split('=');
        cookie[key.trim()] = value;
    })
    return cookie[cookieName];
}

// Update client reg-info
document.querySelector(".account-info-save-button").addEventListener("click", (event) => {
    const client_name = document.querySelector(".input-1").value;
    const client_email = document.querySelector(".input-2").value;
    const client_phone_number = document.querySelector(".input-3").value;
    const client_business_name = document.querySelector(".input-4").value;
    const client_pincode = document.querySelector(".input-6").value;
    const new_password = document.querySelector(".input-7").value;
    const confirm_password = document.querySelector(".input-8").value;
    const current_password = document.querySelector(".input-9").value;
    let msg = "";

    if (current_password == "" || current_password.length < 8 || client_name == "" || client_email == "" || client_phone_number == "" || client_business_name == "" || client_pincode == "") {
        event.preventDefault();

        if (current_password == "") {
            msg = "Current password";
            document.querySelector(".pen-9").click();
        }

        else if (client_name == "") {
            msg = "Name";
            document.querySelector(".pen-1").click();
        }

        else if (client_email == "") {
            msg = "Email";
            document.querySelector(".pen-2").click();
        }

        else if (client_phone_number == "") {
            msg = "Phone Number";
            document.querySelector(".pen-3").click();
        }

        else if (client_business_name == "") {
            msg = "Business Name";
            document.querySelector(".pen-4").click();
        }

        else if (client_pincode == "") {
            msg = "PinCode";
            document.querySelector(".pen-6").click();
        }

        else if (new_password != "" && confirm_password == "") {
            msg = "Confirm Password";
            document.querySelector(".pen-8").click();
        }

        msg += " field cannot be empty";

        showToast(msg);
    }

})

function showToast(message) {
    var x = document.getElementById("snackbar");

    x.className = "show";
    document.querySelector("#snackbar").innerHTML = message;
    setTimeout(function () { x.className = x.className.replace("show", ""); }, 3000);
}


function showPassword(eyeIconID, fieldID) {
    const togglePassword = document.getElementById(eyeIconID);

    const toggleButtonClass = togglePassword.className === "uil uil-eye-slash" ? "uil uil-eye" : "uil uil-eye-slash";
    togglePassword.setAttribute('class', toggleButtonClass);

    if (fieldID == "accountInfoNewPassword") {
        const registerPasswordField = document.getElementById("accountInfoNewPassword");
        const registerPasswordType = registerPasswordField.getAttribute('type') === "password" ? "text" : "password";
        registerPasswordField.setAttribute("type", registerPasswordType);
    
        const confirmClientPasswordField = document.getElementById("accountInfoConfirmPassword");
        const confirmPasswordType = confirmClientPasswordField.getAttribute('type') === "password" ? "text" : "password";
        confirmClientPasswordField.setAttribute("type", confirmPasswordType);
    } else { 
        const passwordField = document.getElementById(fieldID);
        const passwordFieldType = passwordField.getAttribute('type') === "password" ? "text" : "password";
        passwordField.setAttribute("type", passwordFieldType);
    }

}

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
    let strongRegex = new RegExp("^(?=.{14,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    let mediumRegex = new RegExp("^(?=.{10,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    let enoughRegex = new RegExp("(?=.{8,}).*", "g");
    let passwordStrengthLabel = document.getElementById("passwordStrengthLabel");
    let passwordStrengthInfo = "";

    if (confirmPassword.value != '') {
        confirmEnteredPassword();
    }
    isPasswordFieldEmpty(inputID, eyeIconID)
    if (newPassword.value == '') {
        passwordStrengthInfo = "";
        newPassword.style.borderBottomColor = "";
    } else {
        if (enoughRegex.test(newPassword.value) == false) {
            passwordStrengthInfo = "More Characters";
            newPassword.style.borderBottomColor = "red";
        } else {
            newPassword.style.borderBottomColor = "#151111";
            if (strongRegex.test(newPassword.value)) {
                passwordStrengthInfo = "Strong";
            } else if (mediumRegex.test(newPassword.value)) {
                passwordStrengthInfo = "Medium";
            } else {
                passwordStrengthInfo = "Weak";
            }
        }
    }
    passwordStrengthLabel.textContent = passwordStrengthInfo;
}


function confirmEnteredPassword() {
    if (confirmPassword.value == '') {
        confirmPassword.style.borderBottomColor = "";
        confirmPasswordErrorIcon.style.visibility = "hidden";
    } else {
        if (newPassword.value != confirmPassword.value) {
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

function getDecimalInput(textInput, evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
    }
    return true;

}

// Update business rules
document.querySelector(".conversion-info-save-button").addEventListener("click", (event) => {
    const current_password = document.querySelector(".input-14").value;

    if (loyalty_coin_value == "" || credit_percentage == "" || debit_percentage == "" || current_password == "" || current_password.length < 8) {
        event.preventDefault();

        if (current_password == "") {
            msg = "Current password";
            document.querySelector(".pen-14").click();
        }

        else if (loyalty_coin_value == "") {
            msg = "Loyalty Coin Value";
            document.querySelector(".pen-11").click();
        }

        else if (credit_percentage == "") {
            msg = "Credit Percentage";
            document.querySelector(".pen-12").click();
        }

        else if (debit_percentage == "") {
            msg = "Debit Percentage";
            document.querySelector(".pen-13").click();
        }

        msg += " field cannot be empty"

        if (current_password != "" && current_password.length < 8) {
            msg = "Invalid Password!";
        }

        showToast(msg);

    }
})

// Add customer transaction 
document.querySelector(".add-transaction-button").addEventListener("click", (event) => {
    const transactionCustomerPhoneNumber = document.querySelector(".customer-phone-number");
    const transactionCustomerPurchaseSum = document.querySelector(".customer-purchase-sum");

    if (transactionCustomerPhoneNumber.value == "" || transactionCustomerPurchaseSum.value == "" || transactionCustomerPhoneNumber.value.length < 10 || transactionCustomerPurchaseSum.value < 1) {
        event.preventDefault();
        if (transactionCustomerPhoneNumber.value == "" || transactionCustomerPhoneNumber.value.length < 10) {
            transactionCustomerPhoneNumber.focus()
            showToast("Invalid Number");
        } else if (transactionCustomerPurchaseSum.value == "" || transactionCustomerPurchaseSum.value < 1) {
            transactionCustomerPurchaseSum.select();
            showToast("Invalid Purchase Amount");
        }
        return;

    } else {
        if (loyalty_coin_value == 0 || credit_percentage == 0 || debit_percentage == 0) {
            event.preventDefault();
            document.querySelector("#businessPage").click();
            document.querySelector(".pen-11").click();
            showToast("Add transaction rules to continue");
            return;

        } else {
            if (getCookie("transactionType") == "credit") { // Credit
                if (document.querySelector("#customerTable").querySelector(".no-data")) {
                    let customerCount = parseInt(getCookie("client_customer_count")) + 1;
                    document.cookie = "client_customer_count=" + customerCount;

                    document.querySelector(".customer-count").innerHTML = customerCount;
                }
                const totalTransactionAmount = parseInt(getCookie("total_transaction_amount")) + parseInt(transactionCustomerPurchaseSum.value);
                document.cookie = "total_transaction_amount=" + totalTransactionAmount;
                document.querySelector(".total-transaction-amount").innerHTML = totalTransactionAmount;
            }

            else {
                const retrievableLoyaltyPoints = (getCookie("debit_percentage") / 100) * transactionCustomerPurchaseSum.value;
                const retrievableAmount = retrievableLoyaltyPoints / 2;
                let customerNetLoyaltyPoints;

                if (document.querySelector("#customerTable").querySelector(".no-data") == null) {
                    customerNetLoyaltyPoints = document.getElementById("genTable").rows[1].cells.item(1).innerHTML.split(">")[2];
                }

                if (document.querySelector("#customerTable").querySelector(".no-data") != null) {
                    event.preventDefault();
                    showToast("Customer doesn't exist!");
                    return;
                }

                if (transactionCustomerPurchaseSum.value < 1000) {
                    event.preventDefault();
                    showToast("Total purchase sum is not sufficient for redemption");
                    return;
                }

                if (retrievableLoyaltyPoints > customerNetLoyaltyPoints) {
                    event.preventDefault();
                    showToast("Customer doesn't have enough loyalty points");
                    return;
                }


                const totalTransactionAmount = parseInt(getCookie("total_transaction_amount")) + (transactionCustomerPurchaseSum.value - retrievableAmount);
                document.cookie = "total_transaction_amount=" + totalTransactionAmount;
                document.querySelector(".total-transaction-amount").innerHTML = totalTransactionAmount;

                // document.querySelector(".total-redemption-amount").innerHTML = parseInt(getCookie("total_redemption_amount")) + retrievableAmount;
                const totalRedemptionAmount = parseInt(parseInt(getCookie("total_redemption_amount")) + retrievableAmount);
                document.cookie = "total_redemption_amount=" + totalRedemptionAmount;
                document.querySelector(".total-redemption-amount").innerHTML = totalRedemptionAmount;
            }
        }

    }
    setTimeout(() => {
        transactionCustomerPhoneNumber.value = "";
        transactionCustomerPurchaseSum.value = "";
        document.cookie = "searchNumber=";
    }, 800);
    transactionCustomerPhoneNumber.focus();
});
