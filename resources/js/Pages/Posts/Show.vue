<script>
import {Head, Link as InertiaLink} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router} from "@inertiajs/vue3";

export default {
    props: {
        post: {
            type: Object,
        }
    },
    components: {
        Link: InertiaLink, Head, AuthenticatedLayout
    },
    methods: {
        deletePost() {
            router.delete(route('post.destroy', this.post.id))
        }
    },
}
</script>

<template>
    <Head title="Dodaj posta" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between items-center w-full">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800"
                >
                    {{ post.title }}
                </h2>
                <div class="flex flex-row items-center gap-2">
                    <Link :href="route('post.index')" class="bg-gray-800 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Powrót
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                    </Link>
                    <button @click="deletePost" class="bg-red-500 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Usuń
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                    <Link :href="route('post.edit', post.id)" class="bg-blue-500 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Edytuj
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex flex-row gap-6">
                    <img :src="post.cover.url" :alt="post.cover.alt" class="w-2/3 shadow-md h-96 object-cover rounded-lg bg-white" />
                    <div class="w-1/3 flex flex-col gap-4 p-4 shadow-md rounded-lg bg-white">
                        <div class="form_group flex flex-col gap-2">
                            <label for="title" class="text-lg font-bold">Tytuł</label>
                            <input type="text" id="title" class="disabled:opacity-75 bg-gray-300 rounded-md text-lg" :value="post.title" disabled/>
                        </div>
                        <div class="form_group flex flex-col gap-2">
                            <label for="date" class="text-lg font-bold">Data Dodania</label>
                            <input type="text" id="date" class="disabled:opacity-75 bg-gray-300 rounded-md text-lg" :value="new Date(post.created_at).toLocaleDateString()" disabled/>
                        </div>
                        <div class="form_group flex flex-col gap-2">
                            <label for="author" class="text-lg font-bold">Autor</label>
                            <input type="text" id="author" class="disabled:opacity-75 bg-gray-300 rounded-md text-lg" :value="post.user.name" disabled/>
                        </div>
                    </div>
                </div>
                <h3 class="my-4 text-4xl font-bold">Treść Posta</h3>
                <div v-html="post.body" class="w-full"></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
