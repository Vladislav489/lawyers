<script>
    const sendRequest = async (url, body) => {
        const options = {
            method: 'POST',
            body
        };

        const response = await fetch(url, options);
        const data = await response.json();

        return {
            status: response.status,
            errors: data.errors
        };
    };

    const clearValidationErrors = (formElement) => {
        formElement.querySelectorAll('input:not([type=hidden])').forEach((input) => {
            input.classList.remove('is-invalid');
            input.nextElementSibling.textContent = '';
        });
    };

    const renderValidationErrors = (errors, formElement) => {
        Object.keys(errors).forEach((field) => {
            const inputElement = formElement.querySelector(`[name=${field}]`);
            inputElement.classList.add('is-invalid');
            inputElement.nextElementSibling.textContent = errors[field][0];
        });
    };

    const setSubmitHandler = (url, formElement) => {
        formElement.addEventListener('submit', (evt) => {
            evt.preventDefault();
            sendRequest(url, new FormData(evt.target)).then((response) => {
                clearValidationErrors(formElement);

                if (response.errors) {
                    renderValidationErrors(response.errors, evt.target);
                }
            });
        });
    }
</script>