const selectRow = (selectBlock, select = false) => {
    const selectBtns = [...selectBlock.querySelectorAll('.select-btn')];
    const selectWindow = selectBlock.querySelector('.select-window');
    let selectItems;
    const subIcons = [...selectBlock.querySelectorAll('.sub-icon')];
    if (selectWindow) {
        selectItems = [...selectWindow.children];
    }


    if (selectBtns) {
        selectBtns.forEach(btn => {
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
            })
        })
    }

    if (select) {
        selectItems.forEach(item => {
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
        selectItems.forEach(item => {
            item.classList.remove('select');
        })
    }
    const toggleIcons = () => {
        subIcons.forEach(icon => {
            icon.classList.toggle('_open');
        })
    }
    const closeWindows = () => {
        document.querySelectorAll('.select-window').forEach(item => {
            item.classList.remove('_open');
            item.style.height = '0px';
        })
    }
}

/* Попапы */
const popups = [...document.querySelectorAll('.popup')];
const setButtons = new Set();
const popupArr = [];
const popUpButtons = [...document.querySelectorAll('.popup-btn')];
popUpButtons.forEach(button => {
    setButtons.add(button);
})

class Popup {
    #button;
    #popup;
    #steps;
    #nextSteps = [];
    #prevSteps = [];
    #popupClose = [];
    #currentStep = 1;
    #popups = popups;

    constructor(dataPopup) {
        this.#button = dataPopup;

        this.#init();
    }

    #init() {
        this.#popupInit();

        if (this.#button) this.#eventHandler();
    }
    #eventHandler() {
        document.querySelectorAll(`[data-popup="${this.#button}"]`).forEach(button => {
            button.addEventListener('click', event => {
                if (this.#currentStep === 0) {
                    this.#nextStepHandler();
                }

                this.#toggleStep();
                this.#popupOpenHandler();
            })
        })

        this.#popupClose.forEach(item => {
            item.addEventListener('click', () => {
                this.#currentStep = 1;
                this.#prevStepHandler();
                this.#toggleStep();
                this.#popupCloseHandler();
            })
        })

        this.#nextSteps.forEach(item => {
            item.addEventListener('click', () => this.#nextStepHandler());
        })
        this.#prevSteps.forEach(item => {
            item.addEventListener('click', () => this.#prevStepHandler());
        })
    }
    #popupInit() {
        this.#popups.forEach(popup => {
            if (popup.getAttribute('id') === this.#button)
                this.#popup = popup;
        })

        if (this.#popup) {
            this.#steps = [...this.#popup.querySelectorAll('.step')];
            this.#nextSteps = [...document.querySelectorAll(`#${this.#popup.getAttribute('id')} > .step .nextStep`)];
            this.#prevSteps = [...document.querySelectorAll(`#${this.#popup.getAttribute('id')} > .step .prevStep`)];
            this.#popupClose = [this.#popup.querySelector('.popup_shadow'), ...document.querySelectorAll(`#${this.#popup.getAttribute('id')} > .step .popup-close`)];
        }
    }

    #popupOpenHandler() {
        if (this.#popup.classList.contains('popup_hide')) this.#popup.classList.remove('popup_hide');

        document.body.classList.add('body_hidden');
    }
    #popupCloseHandler() {
        if (!this.#popup.classList.contains('popup_hide')) this.#popup.classList.add('popup_hide');

        if (this.#popup.dataset.toggle === 'no') {
            return;
        }
        document.body.classList.remove('body_hidden');
    }
    #nextStepHandler() {
        this.#currentStep += 1;
        this.#toggleStep();
    }
    #prevStepHandler() {
        this.#currentStep -= 1;
        this.#toggleStep();
    }
    #toggleStep() {
        if (this.#steps) {
            this.#steps.forEach(step => {
                step.classList.add('step_hide');

                if (+step.dataset.step === this.#currentStep) step.classList.toggle('step_hide');
            })
        }
    }
}

setButtons.forEach(button => {
    popupArr.push(new Popup(button.dataset.popup));
})


/* stopPropagation */
document.querySelectorAll('.stopPropagation').forEach(item => {
    item.addEventListener('click', (event) => {
        event.stopPropagation();
        event.preventDefault();
    })
})