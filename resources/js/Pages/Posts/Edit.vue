<script>
import {Link as InertiaLink, Head, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router} from "@inertiajs/vue3";
import 'ckeditor5/ckeditor5.css';
import {
    AccessibilityHelp,
    Alignment,
    Autoformat,
    AutoImage,
    Autosave,
    BalloonToolbar,
    BlockQuote,
    BlockToolbar,
    Bold, ClassicEditor,
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
    LinkImage,
    List,
    Link,
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
    TextTransformation, TodoList, Underline, Undo
} from "ckeditor5";
import translations from 'ckeditor5/translations/pl.js';


export default {
    props: {
        post: {
            type: Object,
        }
    },
    components: {
        Link: InertiaLink, Head, AuthenticatedLayout
    },
    data() {
        return{
            isLayoutReady: false,
            config: null, // CKEditor needs the DOM tree before calculating the configuration.
            editor: ClassicEditor,
            form: useForm({
                title: this.post.title,
                body: this.post.body,
            }),
            imagePreview: this.post.cover.url
        }
    },
    methods: {
        handleImageChange(e) {
            const reader = new FileReader()
            reader.addEventListener('load', () => {
                this.imagePreview = reader.result
            })
            reader.readAsDataURL(e.target.files[0])
            this.form = useForm({
                ...this.form,
                image: e.target.files[0]
            })
        }
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
    <Head title="Edytuj Posta" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between items-center w-full">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800"
                >
                    Edycja - {{ post.title }}
                </h2>
                <div class="flex flex-row items-center gap-2">
                    <Link :href="route('post.show', post.id)" class="bg-gray-800 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Powrót
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <form class="mx-auto max-w-7xl sm:px-6 lg:px-8" @submit.prevent="form.post(route('post.update', post.id))">
                <div class="flex flex-row gap-6">
                    <img :src="imagePreview" class="w-2/3 shadow-md h-96 object-cover rounded-lg bg-white" />
                    <div class="w-1/3 flex flex-col gap-4 p-4 shadow-md rounded-lg bg-white">
                        <div class="form_group flex flex-col gap-2">
                            <label for="title" class="text-lg font-bold">Tytuł</label>
                            <input type="text" class="disabled:opacity-75 rounded-md text-lg" v-model="form.title"/>
                        </div>
                        <div class="form_group flex flex-col gap-2">
                            <label for="date" class="text-lg font-bold">Data Dodania</label>
                            <input type="text" class="disabled:opacity-75 bg-gray-300 rounded-md text-lg" :value="new Date(post.created_at).toLocaleDateString()" disabled/>
                        </div>
                        <div class="form_group flex flex-col gap-2">
                            <label for="author" class="text-lg font-bold">Autor</label>
                            <input type="text" class="disabled:opacity-75 bg-gray-300 rounded-md text-lg" :value="post.user.name" disabled/>
                        </div>
                        <label for="image" class="p-3 flex justify-between bg-gray-800 text-white cursor-pointer rounded-md">Zmień zdjęcie
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </label>
                        <input type="file" id="image" hidden @change="handleImageChange">
                    </div>
                </div>
                <h3 class="my-4 text-4xl font-bold">Treść Posta</h3>
                <!--CKEditor-->
                <div class="editor-container editor-container_classic-editor editor-container_include-block-toolbar" ref="editorContainerElement">
                    <div class="editor-container__editor">
                        <div ref="editorElement">
                            <ckeditor v-if="isLayoutReady" v-model="form.body" :editor="editor" :config="config"/>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-end gap-2 w-full mt-4">
                    <Link :href="route('post.show', post.id)" class="bg-gray-800 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Powrót
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                    </Link>
                    <button type="submit" class="bg-green-500 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Zapisz
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
