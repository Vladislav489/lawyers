/*const selectRow = (selectBlock, select = false) => {
    const selectBtns = [...selectBlock.querySelectorAll('.js_select-btn')];
    const selectWindow = selectBlock.querySelector('.js_select-window');

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
        document.querySelectorAll('.js_select-window').forEach((item) => {
            item.classList.remove('_open');
            item.style.height = '0px';
        })
    }
}*/
$(function(){
    /*document.querySelectorAll('.js_nav > li').forEach(item => {
        selectRow(item);
    });*/
    $('.js_select-btn').click(function(){
        $(this).closest('li').toggleClass('active');
        $(this).next().slideToggle(300);
    });
    $('.js_open_profile_nav').click(function(){
        $(this).toggleClass('active');
        $(this).next().fadeToggle();
    });
    /* Select */
    $('.js_select').select2({
        minimumResultsForSearch: -1,
    });
});
