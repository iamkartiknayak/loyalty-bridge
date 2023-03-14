const sidebar = document.querySelector("nav");
const sidebarToggle = document.querySelector(".sidebar-toggle");
const navlinks = document.querySelectorAll(".nav-links li");
const selectCategory = document.querySelector("#selectCategory");
const sortCategory = document.querySelector("#sortCategory");
const categoryIcon = document.querySelector("#categoryIcon");
const typeIcon = document.querySelector("#typeIcon");
const selectType = document.querySelector("#selectType");
const sortType = document.querySelector("#sortType");
const categoryOptions = document.querySelectorAll(".categoryOptions");
const typeOptions = document.querySelectorAll(".typeOptions")
const categoryList = document.querySelector(".categoryList");
const typeList = document.querySelector(".typeList");

window.onload = () => {
    document.cookie = "searchTerm=";
    document.cookie = "sortCategory=client_number";
    document.cookie = "sortType=descending";
    // document.querySelector("#dashboardPage").click();
    updateClientTable();
}


setInterval(updateClientTable, 1000);


// Updates the table using db values
function updateClientTable() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        document.querySelector("#clientTable").innerHTML = this.responseText;
    }
    xhttp.open("GET", "./backend/realtime_client_table.php");
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


function getSortCategoryIcon(category) {
    if (category == "Date") {
        categoryIcon.className = "uil uil-calendar-alt";
        document.cookie = "sortCategory=client_number";
    }
    else if (category == "Amount") {
        categoryIcon.className = "uil uil-rupee-sign";
        document.cookie = "sortCategory=total_transaction_amount";
    }
    categoryList.classList.toggle("active")
    updateClientTable();

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
    updateClientTable();
}


typeOptions.forEach((option) => option.addEventListener("click", function () {
    sortType.innerHTML = this.textContent;
    getSortTypeIcon(this.textContent.trim());
}
));


selectCategory.addEventListener("click", () => {
    categoryList.classList.toggle("active");
    typeList.classList.remove("active");
})


selectType.addEventListener("click", () => {
    typeList.classList.toggle("active");
    categoryList.classList.remove("active");
});


function getNumberInfo() {
    let searchTerm = document.querySelector("#clientNumber").value;
    document.cookie = "searchTerm=" + searchTerm;
    updateClientTable();
}

