document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('form');
    const dobInput = document.querySelector('input[name="dob"]');
    const submitButton = document.querySelector('input[type="submit"]');

    checkUnderAgeCookie();

    form.addEventListener('submit', function(event) {
        const dob = new Date(dobInput.value);
        const today = new Date();
        const age = today.getFullYear() - dob.getFullYear();
        const monthDifference = today.getMonth() - dob.getMonth();
        const dayDifference = today.getDate() - dob.getDate();

        if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
            age--;
        }

        if (age < 13) {
            event.preventDefault();
            alert("You must be at least 13 years old to sign up.");
        }
    });

    function checkUnderAgeCookie() {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            cookie = cookie.trim();
            if (cookie.startsWith('underAge=')) {
                const value = cookie.substring('underAge='.length);
                if (value === 'true') {
                    disableSignUp();
                    return;
                }
            }
        }
    }

    function disableSignUp() {
        submitButton.disabled = true;
        alert("Sorry, you are not allowed to sign up because you'r under 13 years old.");
    }
});
