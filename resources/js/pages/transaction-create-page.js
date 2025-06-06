document.addEventListener('DOMContentLoaded', () => {
  const categoriesByType = window.categoriesByType || {};
  const oldCategoryId = window.oldCategoryId;

  const typeSelect = document.getElementById('type');
  const categorySelect = document.getElementById('category_id');

  if (!typeSelect || !categorySelect) return; // exit if elements not found

  function populateCategories(type) {
    categorySelect.innerHTML = '<option value="">-- Select Category --</option>';

    if (categoriesByType[type]) {
      categoriesByType[type].forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.id;
        option.textContent = cat.name;

        if (parseInt(oldCategoryId) === cat.id) {
          option.selected = true;
        }

        categorySelect.appendChild(option);
      });
    }
  }

  // Populate categories on page load if type is already selected (e.g. after validation fail)
  const selectedType = typeSelect.value;
  if (selectedType) {
    populateCategories(selectedType);
  }

  // Update categories on type change
  typeSelect.addEventListener('change', () => {
    populateCategories(typeSelect.value);
  });
});
