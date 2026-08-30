document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.querySelector('input[type="file"]');
    const form = document.querySelector('form');

    if (!fileInput || !form) {
        console.log('File input or form not found');
        return;
    }

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            form.requestSubmit();
        }
    });
});
