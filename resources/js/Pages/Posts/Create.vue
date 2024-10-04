<script>

import {Head, Link as InertiaLink} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";

import {
    ClassicEditor,
    AccessibilityHelp,
    Alignment,
    Autoformat,
    AutoImage,
    Autosave,
    BalloonToolbar,
    BlockQuote,
    BlockToolbar,
    Bold,
    Code,
    CodeBlock,
    Essentials,
    Heading,
    Highlight,
    ImageBlock,
    ImageCaption,
    ImageInline,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageStyle,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    MediaEmbed,
    Paragraph,
    PasteFromOffice,
    SelectAll,
    SimpleUploadAdapter,
    SourceEditing,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Subscript,
    Superscript,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextTransformation,
    TodoList,
    Underline,
    Undo
} from 'ckeditor5';

import translations from 'ckeditor5/translations/pl.js';

import 'ckeditor5/ckeditor5.css';

export default {
    data() {
        return {
            isLayoutReady: false,
            config: null, // CKEditor needs the DOM tree before calculating the configuration.
            editor: ClassicEditor,
            form: useForm({
                title: "",
                body: "",
                coverImage: "",
                imageName: "Tutaj pojawi się nazwa przesłanego zdjęcia!",
            }),
            allImages: [],
            currentImages: []
        };
    },
    watch: {
    },
    components: {
        AuthenticatedLayout,
        Head,
        Link: InertiaLink,
        TextInput,
        InputLabel
    },
    methods: {
        handleImageUpload(e) {
            const image = e.target.files[0]
            if (image.size >= 2097152) {
                e.target.value = ""
                alert("Plik jest zbyt duży! (max 2MB)")
            } else {
                this.form.imageName = image.name
                this.form.coverImage = image
            }
        },
    },
    mounted() {
        this.config = {
            toolbar: {
                items: [
                    'undo',
                    'redo',
                    '|',
                    'sourceEditing',
                    '|',
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'subscript',
                    'superscript',
                    'code',
                    '|',
                    'specialCharacters',
                    'link',
                    'insertImage',
                    'mediaEmbed',
                    'insertTable',
                    'highlight',
                    'blockQuote',
                    'codeBlock',
                    '|',
                    'alignment',
                    '|',
                    'bulletedList',
                    'numberedList',
                    'todoList',
                    'outdent',
                    'indent'
                ],
                shouldNotGroupWhenFull: true
            },
            plugins: [
                AccessibilityHelp,
                Alignment,
                Autoformat,
                AutoImage,
                Autosave,
                BalloonToolbar,
                BlockQuote,
                BlockToolbar,
                Bold,
                Code,
                CodeBlock,
                Essentials,
                Heading,
                Highlight,
                ImageBlock,
                ImageCaption,
                ImageInline,
                ImageInsert,
                ImageInsertViaUrl,
                ImageResize,
                ImageStyle,
                ImageTextAlternative,
                ImageToolbar,
                ImageUpload,
                Indent,
                IndentBlock,
                Italic,
                Link,
                LinkImage,
                List,
                ListProperties,
                MediaEmbed,
                Paragraph,
                PasteFromOffice,
                SelectAll,
                SimpleUploadAdapter,
                SourceEditing,
                SpecialCharacters,
                SpecialCharactersArrows,
                SpecialCharactersCurrency,
                SpecialCharactersEssentials,
                SpecialCharactersLatin,
                SpecialCharactersMathematical,
                SpecialCharactersText,
                Subscript,
                Superscript,
                Table,
                TableCaption,
                TableCellProperties,
                TableColumnResize,
                TableProperties,
                TableToolbar,
                TextTransformation,
                TodoList,
                Underline,
                Undo
            ],
            balloonToolbar: ['bold', 'italic', '|', 'link', 'insertImage', '|', 'bulletedList', 'numberedList'],
            blockToolbar: ['bold', 'italic', '|', 'link', 'insertImage', 'insertTable', '|', 'bulletedList', 'numberedList', 'outdent', 'indent'],
            heading: {
                options: [
                    {
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            image: {
                toolbar: [
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'imageStyle:inline',
                    'imageStyle:wrapText',
                    'imageStyle:breakText',
                    '|',
                    'resizeImage'
                ]
            },
            language: 'pl',
            simpleUpload: {
                uploadUrl: route('image.store'),
                headers: {
                    'X-CSRF-TOKEN': this.$page.props.auth.csrf
                }
            },
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            placeholder: 'Napisz lub wklej treść swojego posta tutaj!',
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
            },
            translations: [translations],
        };

        this.isLayoutReady = true;
    }
}
</script>

<template>
    <Head title="Dodaj posta" />

    <AuthenticatedLayout>`
        <template #header>
            <div class="flex flex-row justify-between items-center w-full">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800"
                >
                    Dodaj Posta
                </h2>
                <Link :href="route('post.index')" class="bg-red-500 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Anuluj
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <form @submit.prevent="form.post(route('post.store'))" class=" flex flex-col gap-6">
                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6 text-gray-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            Witaj w panelu dodawania posta! Wypełnij wymagane pola i dodaj posta ;)
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputLabel>Tytuł</InputLabel>
                        <input v-model="form.title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <!--CKEditor-->
                    <div class="editor-container editor-container_classic-editor editor-container_include-block-toolbar" ref="editorContainerElement">
                        <div class="editor-container__editor">
                            <div ref="editorElement">
                                <ckeditor v-if="isLayoutReady" v-model="form.body" :editor="editor" :config="config"/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-row items-center bg-gray-200 rounded-md overflow-hidden shadow">
                            <label for="cover" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white px-3 py-2 w-fit">
                                {{ form.imageName === "Tutaj pojawi się nazwa przesłanego zdjęcia!" ? "Prześlij zdjęcie" : "Zmień zdjęcie"}}
                            </label>
                            <p class="px-2 h-full block">{{ form.imageName }}</p>
                            <input type="file" hidden="" id="cover" @change="handleImageUpload">
                        </div>
                    </div>
                    <div class="flex flex-row gap-4 items-center flex-row-reverse">
                        <button type="submit" class="bg-gray-800 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2 self-end w-fit" :disabled="form.processing">Dodaj posta
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        <Link :href="route('post.index')" class="bg-red-500 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2 self-end w-fit">Anuluj
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
