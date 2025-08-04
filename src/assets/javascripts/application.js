import { Application, Controller } from '@hotwired/stimulus';

const application = Application.start();

application.register('navigation', class extends Controller {
    static get targets () {
        return ['button'];
    }

    connect () {
        this.element.addEventListener('keydown', this.trapEscape.bind(this));
    }

    trapEscape (event) {
        if (event.key === 'Escape') {
            this.close();
        }
    }

    switch () {
        if (this.buttonTarget.getAttribute('aria-expanded') === 'true') {
            this.buttonTarget.setAttribute('aria-expanded', 'false');
        } else {
            this.buttonTarget.setAttribute('aria-expanded', 'true');
        }
    }

    close() {
        this.buttonTarget.setAttribute('aria-expanded', 'false');
        this.buttonTarget.focus();
    }
})

application.register('profile', class extends Controller {
    static get targets() {
        return ['sectionNatural', 'sectionLegal', 'firstName', 'lastName', 'legalName', 'controlAddress'];
    }

    initialize() {
        this.switchAddressForNode(this.controlAddressTarget);

        const entityTypeNode = document.querySelector('input[name="entity_type"][checked]');
        this.switchEntityTypeForNode(entityTypeNode);
    }

    switchAddress(event) {
        this.switchAddressForNode(event.target);
    }

    switchAddressForNode(node) {
        const addressNode = document.querySelector('#address');
        addressNode.hidden = !node.checked;
    }

    switchEntityType(event) {
        this.switchEntityTypeForNode(event.target);
    }

    switchEntityTypeForNode(node) {
        if (node.value === 'natural') {
            this.sectionNaturalTarget.hidden = false;
            this.sectionLegalTarget.hidden = true;

            this.firstNameTarget.required = true;
            this.lastNameTarget.required = true;
            this.legalNameTarget.required = false;

            this.controlAddressTarget.checked = this.controlAddressTarget.defaultChecked;
            this.controlAddressTarget.hidden = false;
        } else {
            this.sectionNaturalTarget.hidden = true;
            this.sectionLegalTarget.hidden = false;

            this.firstNameTarget.required = false;
            this.lastNameTarget.required = false;
            this.legalNameTarget.required = true;

            this.controlAddressTarget.checked = true;
            this.controlAddressTarget.hidden = true;
        }

        this.switchAddressForNode(this.controlAddressTarget);
    }
})

application.register('amount-selector', class extends Controller {
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
