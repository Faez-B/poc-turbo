// @ts-nocheck
import { Controller } from '@hotwired/stimulus';

// Config
const isOpenClass = "modal-is-open";
const openingClass = "modal-is-opening";
const closingClass = "modal-is-closing";
const scrollbarWidthCssVar = "--pico-scrollbar-width";
const animationDuration = 400; // ms

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['dialog'];

    connect() {
        // Close with a click outside
        document.addEventListener("click", (event) => {
            const modalContent = this.dialogTarget.querySelector("article");
            const isClickInside = modalContent.contains(event.target);
            const isClickOnLaunchButton = document.querySelector('.modal-container button') === event.target;

            this.dialogTarget.open && !isClickOnLaunchButton && !isClickInside && this.closeModal();
        });

        // Close with Esc key
        document.addEventListener("keydown", (event) => {
            if (this.dialogTarget.open && event.key === "Escape") {
                this.closeModal();
            }
        });
    }

    toggleModal = (event) => {
        event.preventDefault();
        this.dialogTarget.open ? this.closeModal() : this.openModal();
    };

    // Open modal
    openModal = () => {
        const { documentElement: html } = document;
        const scrollbarWidth = getScrollbarWidth();
        if (scrollbarWidth) {
            html.style.setProperty(scrollbarWidthCssVar, `${scrollbarWidth}px`);
        }
        html.classList.add(isOpenClass, openingClass);
        setTimeout(() => {
            html.classList.remove(openingClass);
        }, animationDuration);
        this.dialogTarget.showModal();
    };

    // Close modal
    closeModal = () => {
        const { documentElement: html } = document;
        html.classList.add(closingClass);
        setTimeout(() => {
            html.classList.remove(closingClass, isOpenClass);
            html.style.removeProperty(scrollbarWidthCssVar);
            this.dialogTarget.close();
        }, animationDuration);
    };
}

// Get scrollbar width
const getScrollbarWidth = () => {
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    return scrollbarWidth;
};

// Is scrollbar visible
const isScrollbarVisible = () => {
    return document.body.scrollHeight > screen.height;
};
