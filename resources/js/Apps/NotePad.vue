<script>
export default {
    name: "notepad",
    data() {
        return {
            navOptions: [
                {
                    label: "Plik",
                    items: [
                        {label: "Nowy", action: null},
                        {label: "Otwórz...", action: null},
                        {label: "Zapisz", action: null},
                        {label: "Zapisz jako...", action: null},
                        {separator: true},
                        {label: "Układ strony", action: null},
                        {label: "Drukuj...", action: null},
                        {separator: true},
                        {label: "Zamknij", action: null}
                    ],
                    active: false,
                },
                {
                    label: "Edycja",
                    active: false,
                    items: [
                        {label: "Cofnij...", action: null},
                        {separator: true},
                        {label: "Wytnij", action: null},
                        {label: "Kopiuj", action: null},
                        {label: "Wklej", action: null},
                        {label: "Usuń", action: null},
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
                        {label: "Zwijaj słowa", action: null},
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
            ]
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
            if (document.querySelector('.actions-bar') && ![...document.querySelector('.actions-bar').querySelectorAll("*")].includes(event.target)) {
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
        <nav class="actions-bar">
            <ul>
                <li v-for="(navOption, index) in navOptions" :key="index" :class="{
                    active: navOption.active,
                }">
                    <p @click="setActive(navOption)">{{ navOption.label }}</p>
                    <ul v-if="navOption.items && navOption.active" class="dropdown">
                        <li v-for="(nestedNavOption, index) in navOption.items" :key="index" :class="{
                            separator: nestedNavOption.separator,
                        }"><p v-if="!nestedNavOption.separator">{{ nestedNavOption.label }}</p></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="editor">
            <textarea></textarea>
            <div class="scroll-horizontal">
                <button class="arrow-up"><img src="icons/Scroll UP.png" alt=""></button>
                <div class="thumb-track"></div>
                <div class="arrow-down"></div>
            </div>
            <div class="scroll-vertical"></div>
            <div class="resize-grip"></div>
        </div>
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
    }
    .dropdown p {
        padding: 0 1rem;
        text-wrap: nowrap;
    }
    textarea {
        position: absolute;
        top: 0;
        left: 0;
        background-color: red;
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
        display: grid;
        grid-template-columns: auto 20px;
        grid-template-rows: auto 20px;
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
    .scroll-horizontal .arrow-up {
        box-shadow: 2px 2px 4px rgba(128, 128, 128, 0.5);
    }
    .scroll-horizontal .arrow-up {
        box-shadow: 2px 2px 4px rgba(128, 128, 128, 0.5);
    }
</style>
