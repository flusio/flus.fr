(() => {
    const application = Stimulus.Application.start();

    document.querySelector('.amount-selector__container').style.display = 'block';
    document.querySelector('.amount-selector__choose-label').style.display = 'inline';

    application.register('amount-selector', class extends Stimulus.Controller {
        static get targets() {
            return ['amount', 'button'];
        }

        initialize() {
            const initialAmount = this.data.get('initialAmount');
            this.setAmount(initialAmount);

            const buttonsDisabled = this.buttonTargets.every((button) => {
                return initialAmount !== button.dataset.value;
            });

            if (buttonsDisabled) {
                this.element.classList.add('amount-selector--disable-buttons');
            }
        }

        setAmount(amount) {
            const amountElement = this.amountTarget;
            amountElement.value = amount;

            this.buttonTargets.forEach((button) => {
                if (amount === button.dataset.value) {
                    button.classList.add('amount-selector__button--active');
                } else {
                    button.classList.remove('amount-selector__button--active');
                }
            });
        }

        select(e) {
            const buttonElement = e.target;
            this.setAmount(buttonElement.dataset.value);
            this.element.classList.remove('amount-selector--disable-buttons');
        }

        change(e) {
            const inputElement = e.target;
            this.setAmount(inputElement.value);
            this.element.classList.add('amount-selector--disable-buttons');
        }
    })
})();
