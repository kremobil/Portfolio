<script>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link
    },
    props: {
        posts: {
            type: Array,

        }
    }
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
                    Posty
                </h2>
                <Link :href="route('post.create')" class="bg-gray-800 py-2 px-3 text-white rounded-md flex items-center flex-row gap-2">Dodaj Posta
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        Witaj w zakładce posty znajdziesz tutaj listę wszystkich postów na stronie. kliknij na posta by wyświetlić jego szczegóły.
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-white shadow-lg rounded-lg mt-4 p-4 flex flex-col items-center gap-4" v-for="post in posts" :key="post.id">
                        <img :src="post.cover.url" :alt="post.cover.alt" class="w-full h-64 object-cover">
                        <h3 class="text-xl font-bold text-center">{{post.title}}</h3>
                        <Link :href="route('post.show', post.id)" class="py-2 px-3 bg-gray-800 text-white rounded-md">Zobacz szczegóły posta</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
