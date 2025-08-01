import "./bootstrap";
import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import Clipboard from "@ryangjchandler/alpine-clipboard";
import "flowbite";
import PopularClassesPagination from "./components/PopularClassesPagination.js";

window.Alpine = Alpine;
window.PopularClassesPagination = PopularClassesPagination;

Alpine.plugin(Clipboard);
Livewire.start();
