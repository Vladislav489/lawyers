const popupArr = [];
const setButtons = new Set();
const popups = [...document.querySelectorAll('.popup')];
const popUpButtons = [...document.querySelectorAll('.popup-btn')];

popUpButtons.forEach(button => {
    setButtons.add(button);
});

class Popup {
    #button;
    #popup;
    #steps;
    #nextSteps = [];
    #prevSteps = [];
    #popupClose = [];
    #currentStep = 1;
    #popups = popups;

    constructor (dataPopup) {
        this.#button = dataPopup;
        this.#init();
    }

    #init() {
        this.#popupInit();
        if (this.#button) {
            this.#eventHandler();
        }
    }

    #eventHandler() {
        document.querySelectorAll(`[data-popup="${this.#button}"]`).forEach((button) => {
            button.addEventListener('click', () => {
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

        this.#nextSteps.forEach((item) => {
            item.addEventListener('click', () => this.#nextStepHandler());
        });

        this.#prevSteps.forEach((item) => {
            item.addEventListener('click', () => this.#prevStepHandler());
        });
    }

    #popupInit() {
        this.#popups.forEach(popup => {
            if (popup.getAttribute('id') === this.#button) {
                this.#popup = popup;
            }
        })

        if (this.#popup) {
            this.#steps = [...this.#popup.querySelectorAll('.step')];
            this.#nextSteps = [...document.querySelectorAll(`#${this.#popup.getAttribute('id')} > .step .nextStep`)];
            this.#prevSteps = [...document.querySelectorAll(`#${this.#popup.getAttribute('id')} > .step .prevStep`)];
            this.#popupClose = [
                this.#popup.querySelector('.popup_shadow'),
                ...document.querySelectorAll(`#${this.#popup.getAttribute('id')} > .step .popup-close`)
            ];
        }
    }

    #popupOpenHandler() {
        document.body.classList.add('body_hidden');

        if (this.#popup.classList.contains('popup_hide')) {
            this.#popup.classList.remove('popup_hide');
        }
    }

    #popupCloseHandler() {
        document.body.classList.remove('body_hidden');

        if (!this.#popup.classList.contains('popup_hide')) {
            this.#popup.classList.add('popup_hide');
        }

        if (this.#popup.dataset.toggle === 'no') {
            return;
        }
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
            this.#steps.forEach((step) => {
                step.classList.add('step_hide');

                if (+step.dataset.step === this.#currentStep) {
                    step.classList.toggle('step_hide');
                }
            })
        }
    }
}

setButtons.forEach((button) => {
    popupArr.push(new Popup(button.dataset.popup));
});

/* stopPropagation */
document.querySelectorAll('.stopPropagation').forEach((item) => {
    item.addEventListener('click', (event) => {
        event.stopPropagation();
        event.preventDefault();
    });
})
