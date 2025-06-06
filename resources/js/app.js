import './bootstrap';
import './pages/transaction-edit-page'
import './pages/transaction-create-page'
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


document.addEventListener('DOMContentLoaded', () => {
    const title = document.getElementById('calendar-title');
    const modal = document.getElementById('calendar-modal');
    const cancelBtn = document.getElementById('cancel-button');

    // Open modal on title click
    title.addEventListener('click', () => {
        modal.classList.remove('hiddens');
    });

    // Close modal on cancel button
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hiddens');
    });

    // Optional: close modal when clicking outside form content
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hiddens');
        }
    });
});