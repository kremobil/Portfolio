<script>
import {Head} from "@inertiajs/vue3";
import Taskbar from "@/Components/Taskbar.vue"
import Desktop from "@/Components/Desktop.vue";
import AppWindow from "@/Layouts/AppWindow.vue";

export default {
    components: {AppWindow, Desktop, Head, Taskbar}
}
</script>

<template>
    <Head title="Portfolio - Pulpit" />

    <section class="system-wrapper" ref="system">
        <Desktop />
        <Taskbar />
        <AppWindow></AppWindow>
    </section>
</template>

<style scoped>
.system-wrapper {
    width: 100%;
    height: 100vh;
    background: url("wallpaper.jpg") center/cover;
    overflow: hidden;
}
</style>
