(() => {
    const application = Stimulus.Application.start();

    application.register('payment-type-selector', class extends Stimulus.Controller {
        static get targets() {
            return ['input'];
        }

        initialize() {
            const initialType = this.data.get('initialType');
            this.setType(initialType);
        }

        setType(type) {
            if (type === 'common_pot') {
                document.getElementById('form-group-amount').style.display = 'block';
            } else {
                document.getElementById('form-group-amount').style.display = 'none';
            }
        }

        change(e) {
            this.setType(e.target.value);
        }
    })
})();
