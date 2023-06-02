(() => {
    const application = Stimulus.Application.start();

    application.register('checkboxes', class extends Stimulus.Controller {
        static get targets() {
            return ['control'];
        }

        initialize() {
            this.controlTargets.forEach(function (node) {
                this.switchForControl(node);
            }.bind(this));
        }

        switch(event) {
            this.switchForControl(event.target);
        }

        switchForControl(control) {
            const selector = control.dataset.checkboxesControl;
            const controlledNodes = document.querySelectorAll(selector);

            controlledNodes.forEach(function (node) {
                node.hidden = !control.checked;
            });
        }
    })
})();
