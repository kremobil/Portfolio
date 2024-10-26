<script>
import {Head} from "@inertiajs/vue3";
import Taskbar from "@/Components/Taskbar.vue"
import Desktop from "@/Components/Desktop.vue";
import AppWindow from "@/Layouts/AppWindow.vue";
import Notepad from "@/Apps/NotePad.vue";

export default {
    components: {Notepad, AppWindow, Desktop, Head, Taskbar},
    data() {
        return  {
            activeWindows: [
                {
                    id: 1,
                    title: "Notatnik",
                    icon: "NOTEPAD.EXE_14_2-4.png",
                    maximized: false,
                    minimized: false,
                    selected: false
                },
                {
                    id: 2,
                    title: "Kalkulator",
                    icon: "/icons/Calculator.png",
                    maximized: false,
                    minimized: true,
                    selected: false
                },
                {
                    id: 3,
                    title: "Paint",
                    icon: "/icons/Paint.png",
                    maximized: false,
                    minimized: true,
                    selected: false
                },
            ]
        }
    },
    methods: {
        changeSelectedWindow(windowId) {
            this.activeWindows.forEach(window => {
                window.selected = window.id === windowId;
            })

            this.activeWindows = [...this.activeWindows.filter(window => window.id!== windowId), this.activeWindows.find(window => window.id === windowId)];
        },
        closeWindow(windowId) {
            this.activeWindows = [...this.activeWindows.filter(window => window.id!== windowId)];
            const notMinimizedWindows = this.activeWindows.filter(window => !window.minimized);
            if (notMinimizedWindows.length > 0) {
                this.changeSelectedWindow(notMinimizedWindows.at(-1).id)
            }
        },
        minimizeWindow(windowId) {
            this.activeWindows.find(window => window.id === windowId).minimized = true;
            const notMinimizedWindows = this.activeWindows.filter(window => !window.minimized);
            if (notMinimizedWindows.length > 0) {
                this.changeSelectedWindow(notMinimizedWindows.at(-1).id)
            }
        },
        selectTaskWindow(windowId) {
            const window = this.activeWindows.find(window => window.id === windowId);
            if (window.minimized) {
                window.minimized = false;
                this.changeSelectedWindow(windowId);
            } else if (!window.selected) {
                this.changeSelectedWindow(windowId);
            } else if (window.selected) {
                this.minimizeWindow(windowId);
            }
        },
        openNewWindow(appName, icon) {
            const newWindow = {
                id: Math.max(...this.activeWindows.map(window => window.id)) + 1,
                title: appName,
                icon: icon,
                maximized: false,
                minimized: false,
                selected: true
            }
            this.activeWindows.push(newWindow);

            this.changeSelectedWindow(newWindow.id);
        }
    }
}
</script>

<template>
    <Head title="Portfolio - Pulpit" />

    <section class="system-wrapper" ref="system">
        <Desktop :default-apps="this.activeWindows.map((window) => ({
            id: window.id,
            title: window.title,
            icon: window.icon
        }))"/>
        <Taskbar :active-tasks="activeWindows" @select-window="selectTaskWindow"/>
        <AppWindow
            v-for="window in activeWindows"
            :key="window.id"
            :selected="window.selected"
            :title="window.title"
            :icon="window.icon"
            :minimized="window.minimized"
            :maximized="window.maximized"
            @close-window="closeWindow(window.id)"
            @maximize-window="window.maximized = !window.maximized"
            @selected-window="changeSelectedWindow(window.id)"
            @minimize-window="minimizeWindow(window.id)"
        >
            <Notepad @close-window="closeWindow(window.id)" @new-file="openNewWindow('Notatnik', 'NOTEPAD.EXE_14_2-4.png')"></Notepad>
        </AppWindow>
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
