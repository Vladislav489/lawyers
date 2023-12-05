<script>
    const tokenElement = document.querySelector('[name="csrf-token"]');

    const sendRequest = async (url, body) => {
        const options = {
            body,
            headers: {
                'X-CSRF-TOKEN': tokenElement.content
            }
        };

        const response = await fetch(url, options);
        const data = await response.json();

        return {
            status: response.status,
            errors: data.errors
        };
    };

    const sendDeleteRequest = async (url) => {
        const options = {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': tokenElement.content
            }
        };

        const response = await fetch(url, options);
        const data = await response.json();

        return {
            status: response.status,
            errors: data.errors
        }
    };

    const clearValidationErrors = (formElement) => {
        formElement.querySelectorAll('input:not([type=hidden]), textarea').forEach((input) => {
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

    const setSubmitHandler = (url, onSuccess) => {
        document.querySelector('form').addEventListener('submit', (evt) => {
            evt.preventDefault();
            sendRequest(url, new FormData(evt.target)).then((response) => {
                clearValidationErrors(evt.target);

                if (response.errors) {
                    renderValidationErrors(response.errors, evt.target);
                } else {
                    onSuccess();
                }
            });
        });
    }
</script>