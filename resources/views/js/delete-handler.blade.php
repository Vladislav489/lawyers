<script>
    const setDeleteBtnHandler = () => {
        setTimeout(() => {
            const deleteBtnElements = document.querySelectorAll('.btn-danger');

            deleteBtnElements.forEach((btnElement) => {
                btnElement.addEventListener('click', (evt) => {
                    const cardElement = evt.target.closest('.card[data-id]');

                    if (cardElement) {
                        const entityId = +cardElement.dataset.id;
                        const url = evt.target.dataset.requestUrl + entityId;

                        blockButton(btnElement);
                        setTimeout(() => {
                            sendDeleteRequest(url).then((response) => {
                                if (!response.errors) {
                                    // window.location.reload();
                                }
                            }).finally(() => {
                                unblockButton(btnElement);
                            });
                        }, 2000);
                    }
                });
            });
        }, 1000);
    };
</script>