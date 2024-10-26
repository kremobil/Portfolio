<script>
export default {
    name: "notepad",
    data() {
        return {
            navOptions: [
                {
                    label: "Plik",
                    items: [
                        {label: "Nowy", action: () => {
                            this.$emit("newFile");
                        }},
                        {label: "Otwórz...", action: null},
                        {label: "Zapisz", action: null},
                        {label: "Zapisz jako...", action: null},
                        {separator: true},
                        {label: "Układ strony", action: null},
                        {label: "Drukuj...", action: null},
                        {separator: true},
                        {label: "Zamknij", action: () => {
                            console.log("closeWindow");
                            this.$emit("closeWindow");
                        }}
                    ],
                    active: false,
                },
                {
                    label: "Edycja",
                    active: false,
                    items: [
                        {label: "Cofnij...", action: () => {
                            this.navOptions.forEach(option => option.active = false);
                            document.querySelector("textarea").focus()
                            const selection = window.getSelection()
                            if (window.isSecureContext && navigator.clipboard) {
                                navigator.clipboard.writeText(selection.toString());
                            } else {
                                document.execCommand('undo')
                            }
                        }},
                        {separator: true},
                        {label: "Wytnij", action: () => {
                            this.navOptions.forEach(option => option.active = false);
                            document.querySelector("textarea").focus()
                            const selection = window.getSelection()
                            if (window.isSecureContext && navigator.clipboard) {
                                navigator.clipboard.writeText(selection.toString());
                            } else {
                                document.execCommand('copy')
                            }

                            selection.deleteFromDocument()
                        }},
                        {label: "Kopiuj", action: () => {
                            this.navOptions.forEach(option => option.active = false);
                            document.querySelector("textarea").focus()
                            const selection = window.getSelection()
                            if (window.isSecureContext && navigator.clipboard) {
                                navigator.clipboard.writeText(selection.toString());
                            } else {
                                document.execCommand('copy')
                            }
                        }},
                        {label: "Wklej", action: () => {
                            this.navOptions.forEach(option => option.active = false);
                            document.querySelector("textarea").focus()
                            if (window.isSecureContext && navigator.clipboard) {
                                console.log(navigator.clipboard.readText());
                            } else {
                                document.execCommand('paste')
                            }
                        }},
                        {label: "Usuń", action: () => {
                            this.navOptions.forEach(option => option.active = false);
                            document.querySelector("textarea").focus()
                            const selection = window.getSelection()

                            selection.deleteFromDocument()
                        }},
                        {separator: true},
                        {label: "Znajdź...", action: null},
                        {label: "Znajdź następny", action: null},
                        {label: "Zastąp...", action: null},
                        {label: "Idź do...", action: null},
                        {separator: true},
                        {label: "Zaznacz wszystko", action: null},
                        {label: "Czas/Data", action: null},
                    ]},
                {
                    label: "Format",
                    active: false,
                    items: [
                        {label: "Zwijaj słowa", action: () => {this.wordWrap =!this.wordWrap;}},
                        {label: "Czcionka", action: null}
                    ]
                },
                {
                    label: "Widok",
                    active: false,
                    items: [
                        {label: "Pasek statusu", action: null},
                    ]
                },
                {
                    label: "Pomoc",
                    active: false,
                    items: [
                        {label: "Temat pomocy", action: null},
                        {label: "Informacje o notatnik", action: null},
                    ]
                }
            ],
            content: "",
            wordWrap: false,
        }
    },
    methods: {
        setActive(navOption) {
            this.navOptions.forEach(option => {
                option.active = false;
            });
            navOption.active = true;
        }
    },
    mounted() {
        document.addEventListener('click', (event) => {
            if (this.$refs.actionBar && ![...this.$refs.actionBar.querySelectorAll("*")].includes(event.target)) {
                this.navOptions.forEach(option => {
                    option.active = false;
                });
            }

        })
    }
}
</script>

<template>
    <section class="notepad_wrapper">
        <nav class="actions-bar" ref="actionBar">
            <ul>
                <li v-for="(navOption, index) in navOptions" :key="index" :class="{
                    active: navOption.active,
                }">
                    <p @click.prevent="setActive(navOption)">{{ navOption.label }}</p>
                    <ul v-if="navOption.items && navOption.active" class="dropdown">
                        <li v-for="(nestedNavOption, index) in navOption.items" :key="index" :class="{
                            separator: nestedNavOption.separator,
                        }"><p v-if="!nestedNavOption.separator" @click="nestedNavOption.action">{{ nestedNavOption.label }}</p></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <textarea v-model="content" :style="{
            whiteSpace: wordWrap? 'wrap' : 'nowrap',
        }"></textarea>
    </section>
</template>

<style scoped>
    .notepad_wrapper {
        width: 100%;
        height: 100%;
        background-color: #fff;
        display: flex;
        align-items: center;
        flex-direction: column;
    }
    nav {
        width: 100%;
        background-color: #ece9d8;
        border: 1px solid #ece9d8;
    }
    ul {
        display: flex;
    }
    li {
        position: relative;
    }
    p {
        padding: 0 0.5rem;
    }
    ul p:hover, .active>p {
        background-color: #1660e8;
        color: #fff;

    }
    .dropdown {
        position: absolute;
        bottom: 0;
        left: 0;
        transform: translateY(100%);
        background-color: #fff;
        box-shadow: 2px 2px 4px #808080;
        border: 1px solid #808080;
        display: flex;
        flex-direction: column;
        width: fit-content;
        padding: 0.1rem;
        z-index: 100;
    }
    .dropdown p {
        padding: 0 1rem;
        text-wrap: nowrap;
    }
    textarea {
        width: 100%;
        height: 100%;
        padding: 0.5rem;
        resize: none;
        grid-column: 1/2;
        grid-row: 1/2;
    }

    .editor {
        width: 100%;
        height: 100%;
        overflow: hidden;
        position: relative;
        border: 1px solid #7f9db9;
    }
    .scroll-vertical {
        width: 100%;
        background-color: #f6f5f0;
        border: 1px solid #f2f1ea;
        height: 100%;
        grid-column: 1/2;
        grid-row: 2/3;
    }
    .scroll-horizontal {
        width: 100%;
        background-color: #f6f5f0;
        border: 1px solid #f2f1ea;
        height: 100%;
        grid-column: 2/3;
        grid-row: 1/2;
    }
    .resize-grip {
        width: 100%;
        height: 100%;
        background-color: #f6f5f0;
        border: 1px solid #f2f1ea;
        grid-column: 2/3;
        grid-row: 2/3;
    }
    .separator {
        display: block;
        width: 100%;
        height: 1px;
        background-color: #808080;
    }

    textarea:focus {
        outline: none;
    }
    .scroll-horizontal {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .scroll-horizontal .arrow-up {
        box-shadow: 2px 2px 4px rgba(128, 128, 128, 0.5);
    }
    .scroll-horizontal .arrow-down {
        box-shadow: -2px -2px 4px rgba(128, 128, 128, 0.5);
        transform: rotateZ(180deg);
    }
    .scroll-horizontal .thumb-track {
        width: 100%;
        height: 100%;
        background-color: cyan;
    }
    .scroll-horizontal .thumb {
        width:100%;
        height: 30%;
        background-color: purple;
    }
    .scroll-vertical {
        width: 100%;
        display: flex;
        height: 100%;
    }
    .scroll-vertical .arrow-left {
        box-shadow: 2px 2px 4px rgba(128, 128, 128, 0.5);
        height: 100%;
        transform: rotateZ(270deg);
    }
    .scroll-vertical img {
        height: 100%;
    }
    .scroll-vertical .arrow-right {
        box-shadow: -2px -2px 4px rgba(128, 128, 128, 0.5);
        height: 100%;
        transform: rotateZ(90deg);
    }
    .scroll-vertical .thumb-track {
        width: 100%;
        height: 100%;
        background-color: cyan;
    }
    .scroll-vertical .thumb {
        width: 30%;
        height: 100%;
        background-color: purple;
    }
</style>
