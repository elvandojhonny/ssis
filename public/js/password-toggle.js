document.addEventListener("DOMContentLoaded", function () {

    const passwordInputs = document.querySelectorAll(".password-toggle");

    passwordInputs.forEach(function (input) {

        // Bungkus input jika belum berada di wrapper
        const wrapper = document.createElement("div");
        wrapper.classList.add("position-relative");

        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        // Buat tombol mata
        const toggleButton = document.createElement("button");

        toggleButton.type = "button";
        toggleButton.className =
            "btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent shadow-none";

        toggleButton.style.zIndex = "10";
        toggleButton.style.paddingRight = "15px";

        toggleButton.innerHTML = `
            <i class="bi bi-eye"></i>
        `;

        wrapper.appendChild(toggleButton);

        // Supaya teks input tidak tertutup ikon
        input.style.paddingRight = "45px";

        // Event buka / tutup password
        toggleButton.addEventListener("click", function () {

            const icon = toggleButton.querySelector("i");

            if (input.type === "password") {

                input.type = "text";

                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");

            } else {

                input.type = "password";

                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");

            }

        });

    });

});