/*  =============================
    ======== KEYBOARD ===========
    =============================
*/
const Keyboard = {
    elements: {
        main: null,
        keysContainer: null,
        language: null,
        keys: [],
        arLayout: false,
        ArabicKeys: [
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9",
            "0",
            "backspace",
            "ض",
            "ص",
            "ث",
            "ق",
            "ف",
            "غ",
            "ع",
            "ه",
            "خ",
            "ح",
            "ج",
            "د",
            "ش",
            "س",
            "ي",
            "ب",
            "ل",
            "ا",
            "ت",
            "ن",
            "م",
            "ك",
            "ط",
            "enter",
            "ئ",
            "ء",
            "ؤ",
            "ر",
            "ى",
            "ة",
            "و",
            "ز",
            "ظ",
            "space"
        ],
        EnglishKeys: [
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9",
            "0",
            "backspace",
            "q",
            "w",
            "e",
            "r",
            "t",
            "y",
            "u",
            "i",
            "o",
            "p",
            "a",
            "s",
            "d",
            "f",
            "g",
            "h",
            "j",
            "k",
            "l",
            "enter",
            "z",
            "x",
            "c",
            "v",
            "b",
            "n",
            "m",
            ".",
            "@",
            "space"
        ]
    },

    eventHandlers: {
        oninput: null,
        onclose: null
    },

    properties: {
        value: "",
        capsLock: false
    },

    init() {
        // Create main elements
        this.elements.main = document.createElement("div");
        this.elements.language = document.createElement("div");
        this.elements.keysContainer = document.createElement("div");

        // Setup main elements
        this.elements.main.classList.add("keyboard", "keyboard--hidden");
        this.elements.language.classList.add("lang-key");
        this.elements.keysContainer.classList.add("keyboard__keys");
        this.elements.keysContainer.appendChild(this._createKeys());

        this.elements.keys = this.elements.keysContainer.querySelectorAll(
            ".keyboard__key"
        );

        // Add to DOM
        this.elements.main.appendChild(this.elements.keysContainer);
        this.elements.main.appendChild(this.elements.language);
        document.body.appendChild(this.elements.main);

        // Automatically use keyboard for elements with .use-keyboard-input
        document.querySelectorAll(".use-keyboard-input").forEach(element => {
            element.addEventListener("focus", () => {
                this.open(element.value, currentValue => {
                    element.value = currentValue;
                });
            });
        });
    },

    _createKeys() {
        const fragment = document.createDocumentFragment();
        let keyLayout = [];

        if (this.elements.arLayout) {
            keyLayout = this.elements.ArabicKeys;
        } else {
            keyLayout = this.elements.EnglishKeys;
        }

        // Creates HTML for an icon
        const createIconHTML = icon_name => {
            return `<i class="${icon_name}"></i>`;
        };

        keyLayout.forEach(key => {
            const keyElement = document.createElement("button");
            const insertLineBreak =
                ["backspace", "p", "enter", "@", "د", "ظ"].indexOf(key) !== -1;

            // Add attributes/classes
            keyElement.setAttribute("type", "button");
            keyElement.classList.add("keyboard__key");

            switch (key) {
                case "backspace":
                    keyElement.classList.add("keyboard__key--wide");
                    keyElement.innerHTML = createIconHTML("fas fa-backspace");

                    keyElement.addEventListener("click", () => {
                        this.properties.value = this.properties.value.substring(
                            0,
                            this.properties.value.length - 1
                        );
                        this._triggerEvent("oninput");
                    });

                    break;

                case "enter":
                    keyElement.classList.add(
                        "keyboard__key--wide",
                        "keyboard__key--dark"
                    );
                    keyElement.innerHTML = createIconHTML(
                        "fas fa-level-down-alt fa-rotate-90"
                    );
                    keyElement.addEventListener("click", () => {
                        this.close();
                        this._triggerEvent("onclose");
                    });

                    break;

                case "space":
                    keyElement.classList.add("keyboard__key--extra-wide");
                    keyElement.innerHTML = createIconHTML("fas fa-minus");

                    keyElement.addEventListener("click", () => {
                        this.properties.value += " ";
                        this._triggerEvent("oninput");
                    });

                    break;

                default:
                    keyElement.textContent = key.toLowerCase();

                    keyElement.addEventListener("click", () => {
                        this.properties.value += this.properties.capsLock
                            ? key.toUpperCase()
                            : key.toLowerCase();
                        this._triggerEvent("oninput");
                    });

                    break;
            }

            fragment.appendChild(keyElement);

            if (insertLineBreak) {
                fragment.appendChild(document.createElement("br"));
            }
        });

        return fragment;
    },

    _triggerEvent(handlerName) {
        if (typeof this.eventHandlers[handlerName] == "function") {
            this.eventHandlers[handlerName](this.properties.value);
        }
    },

    open(initialValue, oninput, onclose) {
        this.properties.value = initialValue || "";
        this.eventHandlers.oninput = oninput;
        this.eventHandlers.onclose = onclose;
        this.elements.main.classList.remove("keyboard--hidden");
    },

    close() {
        this.properties.value = "";
        this.eventHandlers.oninput = oninput;
        this.eventHandlers.onclose = onclose;
        this.elements.main.classList.add("keyboard--hidden");
    }
};

window.addEventListener("DOMContentLoaded", function() {
    Keyboard.init();
});

$("body").on("click", ".lang-key", function() {
    Keyboard.elements.main.remove();
    Keyboard.elements.arLayout = !Keyboard.elements.arLayout;
    Keyboard.init();
    Keyboard.open();
});
