document.addEventListener('DOMContentLoaded', function () {
    const categoriesByType = window.categoriesByType;
    const selectedCategoryId = window.selectedCategoryId;

    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');

    function populateCategories(type) {
        categorySelect.innerHTML = '<option value="">-- Select Category --</option>';

        if (categoriesByType[type]) {
            categoriesByType[type].forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.name;

                if (parseInt(selectedCategoryId) === cat.id) {
                    option.selected = true;
                }

                categorySelect.appendChild(option);
            });
        }
    }

    const selectedType = typeSelect.value;
    if (selectedType) {
        populateCategories(selectedType);
    }

    typeSelect.addEventListener('change', function () {
        populateCategories(this.value);
    });
});
