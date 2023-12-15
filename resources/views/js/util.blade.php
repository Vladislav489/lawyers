<!-- spinner -->
<template id="spinner">
    <span class="spinner-border spinner-border-sm"></span>
</template>

<script>
    const spinnerTemplate = document.getElementById('spinner')
        .content
        .querySelector('.spinner-border');

    const blockButton = function (buttonElement, spinner = spinnerTemplate) {
        const text = buttonElement.dataset.text;
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
</script>