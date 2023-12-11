<script>
    const tokenElement = document.querySelector('[name="csrf-token"]');
    const spinnerTemplate = document.getElementById('spinner')
        .content
        .querySelector('.spinner-border');

    const sendPostRequest = async (url, body) => {
        const options = {
            method: 'POST',
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

    const blockButton = function (buttonElement, text, spinner = spinnerTemplate) {
        blockButton.prevTextContent = text ? buttonElement.textContent : null;
        buttonElement.disabled = true;
        buttonElement.style.cursor = 'not-allowed';

        if (blockButton.prevTextContent) {
            const spinnerElement = spinner.cloneNode(true);
            buttonElement.innerHTML = '';
            buttonElement.insertAdjacentElement('beforeend', spinnerElement);
            buttonElement.insertAdjacentText('beforeend', ` ${text}...`);
        }
    };

    const unblockButton = (buttonElement) => {
        buttonElement.disabled = false;
        buttonElement.style.cursor = 'pointer';

        if (blockButton.prevTextContent) {
            buttonElement.innerHTML = blockButton.prevTextContent;
        }
    };

    const setSubmitHandler = (url, onSuccess, btnText, selector = 'form', validate = true) => {
        const formElement = document.querySelector(selector);
        const submitBtnElement = formElement.querySelector('[type=submit]');

        formElement.addEventListener('submit', (evt) => {
            evt.preventDefault();
            blockButton(submitBtnElement, btnText);
            setTimeout(() => {
                sendPostRequest(url, new FormData(evt.target)).then((response) => {
                    clearValidationErrors(evt.target);

                    if (response.errors && validate) {
                        renderValidationErrors(response.errors, evt.target);
                    } else if (!response.errors) {
                        onSuccess();
                    }
                }).finally(() => {
                    unblockButton(submitBtnElement);
                });
            }, 2000);
        });
    }
</script>