import './bootstrap';

// -------------------------------------------------------------------------------------
// Tall-Toasts
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts';
Alpine.plugin(ToastComponent)

// -------------------------------------------------------------------------------------
// @popperjs/core (needed for laravel-simple-select)
import { createPopper } from "@popperjs/core";
window.createPopper = createPopper;

// -------------------------------------------------------------------------------------
// Tailwind Elements
import {
    Collapse,
    Datepicker,
    Dropdown,
    Input,
    Lightbox,
    Modal,
    Offcanvas,
    Ripple,
    Tab,
    Toast,
    Validation,
    initTE,
} from "tw-elements";

initTE({
    Collapse,
    Datepicker,
    Dropdown,
    Input,
    Lightbox,
    Modal,
    Offcanvas,
    Ripple,
    Tab,
    Toast,
    Validation,
}, { allowReinits: true });

document.addEventListener('livewire:navigated', () => {
    initTE({
        Collapse,
        Datepicker,
        Dropdown,
        Input,
        Lightbox,
        Modal,
        Offcanvas,
        Ripple,
        Tab,
        Toast,
        Validation,
    });
});

// -------------------------------------------------------------------------------------
// Back to top button
// -------------------------------------------------------------------------------------
// get the button
const mybutton = document.getElementById("btn-back-to-top");

// when the user scrolls down 20px from the top of the document, show the button
const scrollFunction = () => {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        mybutton.classList.remove("hidden");
    } else {
        mybutton.classList.add("hidden");
    }
};

// scroll back to top
const backToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

// when the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", backToTop);

window.addEventListener("scroll", scrollFunction);
// -------------------------------------------------------------------------------------
