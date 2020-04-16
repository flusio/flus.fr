(() => {
    const application = Stimulus.Application.start();

    application.register('amount-selector', class extends Stimulus.Controller {
        static get targets() {
            return ['amount', 'button'];
        }

        initialize() {
            const initialAmount = this.data.get('initialAmount');
            this.setAmount(initialAmount);
        }

        setAmount(amount) {
            const amountElement = this.amountTarget;
            amountElement.value = amount;

            this.buttonTargets.forEach((button) => {
                const buttonValue = button.dataset.value;
                if (amount === buttonValue) {
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
