<script>
    const tokenElement = document.querySelector('[name="csrf-token"]');

    const getDataArray = async (urls) => {
        const requests = urls.map((url) => fetch(url));
        const responses = await Promise.all(requests);
        const data = await Promise.all(responses.map((r) => r.json()));

        const result = [];
        data.forEach((dataItem, i) => {
            result.push({
                data: dataItem,
                status: responses[i].status,
                errors: null,
            });
        });

        return {
            data,
            status: null,
            errors: null
        };
    }

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

    const setSubmitHandler = (selector = 'form', validate = true) => {
        const formElement = document.querySelector(selector);
        const submitBtnElement = formElement.querySelector('[type=submit]');

        formElement.addEventListener('submit', (evt) => {
            evt.preventDefault();
            blockButton(submitBtnElement);
            setTimeout(() => {
                const url = evt.target.dataset.requestUrl;
                sendPostRequest(url, new FormData(evt.target)).then((response) => {
                    clearValidationErrors(evt.target);

                    if (response.errors && validate) {
                        renderValidationErrors(response.errors, evt.target);
                    } else if (!response.errors) {
                        window.location.href = evt.target.dataset.successUrl;
                    }
                }).finally(() => {
                    unblockButton(submitBtnElement);
                });
            }, 2000);
        });
    }
</script>
