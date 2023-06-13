(() => {
    const application = Stimulus.Application.start();

    application.register('profile', class extends Stimulus.Controller {
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
                this.controlAddressTarget.disabled = false;
            } else {
                this.sectionNaturalTarget.hidden = true;
                this.sectionLegalTarget.hidden = false;

                this.firstNameTarget.required = false;
                this.lastNameTarget.required = false;
                this.legalNameTarget.required = true;

                this.controlAddressTarget.checked = true;
                this.controlAddressTarget.disabled = true;
            }

            this.switchAddressForNode(this.controlAddressTarget);
        }
    })
})();
