document.addEventListener("DOMContentLoaded", function () {

    const forms = document.querySelectorAll("form");

    forms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            const password =
                form.querySelector('input[name="password"]');

            if (password && password.value.length < 6) {

                alert(
                    "Password must contain at least 6 characters."
                );

                event.preventDefault();
            }

        });

    });

});