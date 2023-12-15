<script>
    const clearValidationErrors = (formElement) => {
        formElement.querySelectorAll('input:not([type=hidden]), textarea').forEach((input) => {
            input.classList.remove('is-invalid');
            if (input.nextElementSibling.matches('.invalid-feedback')) {
                input.nextElementSibling.textContent = '';
            }
        });
    };

    const renderValidationErrors = (errors, formElement) => {
        Object.keys(errors).forEach((field) => {
            fieldName = field === 'avatar_path' ? 'avatar' : field;
            const inputElement = formElement.querySelector(`[name=${fieldName}]:not([type=hidden])`);
            if (inputElement) {
                inputElement.classList.add('is-invalid');
                inputElement.nextElementSibling.textContent = errors[field][0];
            }
        });
    };
</script>