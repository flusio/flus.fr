(() => {
    const application = Stimulus.Application.start();

    application.register('amount-selector', class extends Stimulus.Controller {
        static get targets() {
            return ['amount', 'radio', 'totalAmount'];
        }

        initialize() {
            const initialAmount = this.data.get('initialAmount');
            this.setAmount(initialAmount);
        }

        setAmount(amount) {
            const amountElement = this.amountTarget;
            amountElement.value = amount;
            this.refreshTotalAmount();
        }

        select(e) {
            const radioElement = e.target;
            this.setAmount(radioElement.dataset.value);
        }

        change(e) {
            this.radioTargets.forEach(function (radio) {
                radio.checked = false;
            });

            this.refreshTotalAmount();
        }

        refreshTotalAmount() {
            if (this.totalAmountTarget) {
                const amount = parseInt(this.amountTarget.value, 10);
                const countAccounts = parseInt(this.data.get('countAccounts'), 10);
                this.totalAmountTarget.innerHTML = amount * countAccounts;
            }
        }
    })
})();
