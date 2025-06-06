document.addEventListener('DOMContentLoaded', () => {
  const categoriesByType = window.categoriesByType || {};
  const oldCategoryId = window.oldCategoryId;

  const btnCashIn = document.getElementById('btn-cash-in');
  const btnCashOut = document.getElementById('btn-cash-out');
  const hiddenInput = document.getElementById('type');
  const categorySelect = document.getElementById('category_id');

  if (!hiddenInput || !categorySelect || !btnCashIn || !btnCashOut) return;

  function updateCategoryOptions(type) {
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

  function selectType(type) {
    hiddenInput.value = type;

    if (type === 'cash_in') {
      btnCashIn.classList.remove('bg-green-300');
      btnCashIn.classList.add('bg-green-600');
      btnCashOut.classList.remove('bg-red-600');
      btnCashOut.classList.add('bg-red-300');
    } else if (type === 'cash_out') {
      btnCashOut.classList.remove('bg-red-300');
      btnCashOut.classList.add('bg-red-600');
      btnCashIn.classList.remove('bg-green-600');
      btnCashIn.classList.add('bg-green-300');
    }

    updateCategoryOptions(type);
  }

  btnCashIn.addEventListener('click', () => selectType('cash_in'));
  btnCashOut.addEventListener('click', () => selectType('cash_out'));

  // Initialize with old value or default to 'cash_in' (or empty)
  if (hiddenInput.value) {
    selectType(hiddenInput.value);
  } else {
    // optional: select cash_in by default
    // selectType('cash_in');
  }
});
