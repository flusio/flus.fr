(() => {
    const application = Stimulus.Application.start();

    application.register('amount-selector', class extends Stimulus.Controller {
        static get targets() {
            return ['amount', 'radio'];
        }

        initialize() {
            const initialAmount = this.data.get('initialAmount');
            this.setAmount(initialAmount);
        }

        setAmount(amount) {
            const amountElement = this.amountTarget;
            amountElement.value = amount;
        }

        select(e) {
            const radioElement = e.target;
            this.setAmount(radioElement.dataset.value);
        }

        change(e) {
            this.radioTargets.forEach(function (radio) {
                radio.checked = false;
            });
        }
    })
})();
