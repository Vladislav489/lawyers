const selectRow = (selectBlock, select = false) => {
    const selectBtns = [...selectBlock.querySelectorAll('.select-btn')];
    const selectWindow = selectBlock.querySelector('.select-window');

    let selectItems;
    const subIcons = [...selectBlock.querySelectorAll('.sub-icon')];

    if (selectWindow) {
        selectItems = [...selectWindow.children];
    }

    if (selectBtns) {
        selectBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                if (selectWindow.classList.contains('_open')) {
                    closeWindows();
                    selectWindow.style.height = '0px';
                    selectWindow.classList.remove('_open');
                } else {
                    closeWindows();
                    selectWindow.style.height = `${selectItems[0].offsetHeight * selectItems.length}px`;
                    selectWindow.classList.add('_open');
                }

                toggleIcons();
            });
        });
    }

    if (select) {
        selectItems.forEach((item) => {
            item.addEventListener('click', () => {
                clearItems();
                item.classList.add('select');
                selectBtns[0].querySelector('p').textContent = item.textContent;

                selectWindow.style.height = '0px';
                selectWindow.classList.remove('_open');
            })
        })
    }

    const clearItems = () => {
        selectItems.forEach((item) => {
            item.classList.remove('select');
        })
    }

    const toggleIcons = () => {
        subIcons.forEach((icon) => {
            icon.classList.toggle('_open');
        })
    }

    const closeWindows = () => {
        document.querySelectorAll('.select-window').forEach((item) => {
            item.classList.remove('_open');
            item.style.height = '0px';
        })
    }
}
