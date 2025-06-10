document.addEventListener("DOMContentLoaded", function () {
    const categoriesByType = window.categoriesByType;
    const selectedCategoryId = window.selectedCategoryId;

    const hiddenTypeInput = document.getElementById("type");
    const categorySelect = document.getElementById("category_id");

    const cashInBtn = document.getElementById("cash_in_btn");
    const cashOutBtn = document.getElementById("cash_out_btn");

    function populateCategories(type) {
        categorySelect.innerHTML =
            '<option value="">-- Select Category --</option>';
        if (categoriesByType[type]) {
            categoriesByType[type].forEach((cat) => {
                const option = document.createElement("option");
                option.value = cat.id;
                option.textContent = cat.name;

                if (parseInt(selectedCategoryId) === cat.id) {
                    option.selected = true;
                }

                categorySelect.appendChild(option);
            });
        }
    }

    function updateButtonStyles(selectedType) {
        if (selectedType === "cash_in") {
            cashInBtn.classList.remove("bg-green-300");
            cashInBtn.classList.add("bg-green-600");
            cashOutBtn.classList.remove("bg-red-600");
            cashOutBtn.classList.add("bg-red-300");
        } else if (selectedType === "cash_out") {
            cashOutBtn.classList.remove("bg-red-300");
            cashOutBtn.classList.add("bg-red-600");
            cashInBtn.classList.remove("bg-green-600");
            cashInBtn.classList.add("bg-green-300");
        }
    }

    function setType(type) {
        hiddenTypeInput.value = type;
        updateButtonStyles(type);
        populateCategories(type);
    }

    cashInBtn.addEventListener("click", function () {
        setType("cash_in");
    });

    cashOutBtn.addEventListener("click", function () {
        setType("cash_out");
    });

    // On load, set the correct button and category list
    const initialType = hiddenTypeInput.value;
    if (initialType) {
        setType(initialType);
    }
});
