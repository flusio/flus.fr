(() => {
    const application = Stimulus.Application.start();

    application.register('popup', class extends Stimulus.Controller {
        open (e) {
            this.element.open = true;
        }

        close (e) {
            if (!this.element.contains(e.target)) {
                this.element.open = false;
            }
        }

        toggle (e) {
            this.element.open = !this.element.open;
        }
    })
})();
