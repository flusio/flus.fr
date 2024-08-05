(() => {
    const application = Stimulus.Application.start();

    application.register('navigation', class extends Stimulus.Controller {
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
})();
