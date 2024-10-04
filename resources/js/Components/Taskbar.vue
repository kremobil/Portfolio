<script>
export default {
    name: "Taskbar",
    data() {
        return {
            menuActive: false,
            tasks: [
                {
                    id: 1,
                    name: "Mój komputer",
                    icon: "netid.dll_14_101-9.png",
                    active: false,
                },
                {
                    id: 2,
                    name: "Internet Explorer",
                    icon: "mshtml.dll_14_2660-1.png",
                    active: false,
                }
            ],
            date: new Date()
        }
    },
    methods: {
        switchTask(selectedTask) {
            this.tasks.forEach((task) => {
                if (task === selectedTask && !task.active) {
                    task.active = true
                } else {
                    task.active = false
                }
            })
        }
    },
    mounted() {
        // Update date
        setInterval(() => {
            this.date = new Date()
        }, 1000)
    }
}
</script>

<template>
<section class="start-menu" :class="{
    active: menuActive
}">
    <div class="user-profile">
        <img src="profile-pic.jpg" alt="" class="profile-pic">
        <h4>Wiktor Fajkowski</h4>
    </div>
    <div class="quick-acces">
        <div class="top-line"></div>
        <div class="quick-acces-programs-wrapper">
            <div class="main-programs">
                <div class="program">
                    <img src="mshtml.dll_14_2660-1.png" alt="internet" class="icon">
                    <div class="details">
                        <h3><b>Internet</b></h3>
                        <h4>Internet explorer</h4>
                    </div>
                </div>
                <div class="program">
                    <img src="msoeres.dll_14_52-3.png" alt="E-mail" class="icon">
                    <div class="details">
                        <h3><b>E-mail</b></h3>
                        <h4>Outlook Express</h4>
                    </div>
                </div>
                <div class="line_divider"></div>
                <div class="program">
                    <img src="shell32.dll_14_239-3.png" alt="MSN Explorer" class="icon">
                    <h3>Msn Explorer</h3>
                </div>
                <div class="program">
                    <img src="wmplayer.exe_14_IDR_MAINFRAME-3.png" alt="WMP" class="icon">
                    <h3>Windows Media Player</h3>
                </div>
                <div class="program">
                    <img src="wmm2res.dll_14_128-3.png" alt="WMM" class="icon">
                    <h3>Windows Movie Maker</h3>
                </div>
                <div class="program">
                    <img src="tourstrt.exe_14_100-6.png" alt="WMM" class="icon">
                    <h3>Tour Windows XP</h3>
                </div>
                <div class="program">
                    <img src="mspaint.exe_14_2-6.png" alt="WMM" class="icon">
                    <h3>Paint</h3>
                </div>
                <div class="line_divider"></div>
                <h4 class="all-programs">
                    Wszystkie programy <img src="allprogs.png" alt="Wszystkie programy">
                </h4>
            </div>
            <div class="other-programs">
                <div class="program">
                    <img src="mydocs.dll_14_100-4.png" alt="Moje dokumenty" class="icon">
                    <h3>Moje dokumenty</h3>
                </div>
                <div class="program">
                    <img src="mydocs.dll_14_101-4.png" alt="Moje dokumenty" class="icon">
                    <h3>Moje Zdjęcia</h3>
                </div>

                <div class="program">
                    <img src="shell32.dll_14_237-8.png" alt="Moja muzyka" class="icon">
                    <h3>Moja Muzyka</h3>
                </div>
                <div class="program">
                    <img src="netid.dll_14_101-4.png" alt="Mój komputer" class="icon">
                    <h3>Mój Komputer</h3>
                </div>
                <div class="line_divider"></div>
                <div class="program">
                    <img src="shell32.dll_14_22-4.png" alt="Panel sterowania" class="icon">
                    <h3>Panel sterowania</h3>
                </div>
                <div class="program">
                    <img src="shell32.dll_14_138-3.png" alt="Drukarki i Fax" class="icon">
                    <h3>Drukarki i Fax</h3>
                </div>
                <div class="line_divider"></div>
                <div class="program">
                    <img src="hnetwiz.dll_14_10001-8.png" alt="Pomoc i wsparcie" class="icon">
                    <h3>Pomoc i wsparcie</h3>
                </div>
                <div class="program">
                    <img src="shell32.dll_14_23-4.png" alt="Wyszukaj" class="icon">
                    <h3>Wyszukaj</h3>
                </div>
                <div class="program">
                    <img src="shell32.dll_14_160-3.png" alt="Uruchom" class="icon">
                    <h3>Uruchom</h3>
                </div>

            </div>
        </div>
    </div>
    <div class="power-options">
        <div class="option">
            <img src="shell32.dll_14_45-4.png" alt="wyloguj">
            Wyloguj
        </div>
        <div class="option">
            <img src="shell32.dll_14_28-8.png" alt="zamknij">
            Zamknij
        </div>
    </div>
</section>
<nav class="taskbar-wrapper">

    <button class="start" @click="menuActive = !menuActive" :class="{
        active: menuActive
    }">
        <img src="win-logo.png" alt=""> start
    </button>

    <div class="tasks">
        <button class="task" v-for="task in tasks" :key="task.id" @click="switchTask(task)" :class="{
            active: task.active
        }">
            <img :src="task.icon" :alt="task.name" />
            <p>{{ task.name }}</p>
        </button>
    </div>

    <div class="clock">
        {{ date.getHours() }}:{{ date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes() }}
    </div>
</nav>
</template>

<style scoped>
.taskbar-wrapper {
    width: 100%;
    height: 60px;
    background-color: #245edc;
    font-family: "WinXp";
    box-shadow: 0 5px 5px 0 #438fe7 inset, 0 -5px 5px 0 rgba(7, 19, 49, 0.75) inset;
    display: flex;
    align-items: center;
    z-index: 1000;
    position: relative;
}

.start img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.start {
    clip-path: circle(80.0% at 40% 50%);
    box-shadow: 5px 5px 10px 0 #94c394 inset, -5px -5px 15px 0 #094248 inset, 2px 2px 2px 0 #94c394 inset, -3px -2px 5px 0 #094248 inset;
    background: linear-gradient(-25deg, #1e981e 75%, #94c394 );
    padding-inline: 2rem;
    width: fit-content;
    min-width: fit-content;
    height: 100%;
    font-size: 42px;
    color: white;
    font-weight: bold;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
}

.start.active {
    box-shadow: 5px 5px 10px 0 #094248 inset, -5px -5px 15px 0 #094248 inset, 2px 2px 2px 0 #094248 inset, -3px -2px 5px 0 #094248 inset;
    background: linear-gradient(-25deg, #1e981e 75%, #094248 );
}

.tasks {
    padding-inline: 1rem;
    display: flex;
    gap: 0.5rem;
    width: 100%;
}
.task {
    background: #3980f4;
    display: flex;
    color: white;
    font-size: 24px;
    width: 20%;
    gap: 1rem;
    min-width: 48px;
    padding-inline: 1rem;
    height: 42px;
    align-items: center;
    border-radius: 0.2rem;
    box-shadow: 3px 2px 4px 0 #70a5f6 inset, -3px -2px 4px 0 #2652b0 inset;
    overflow: hidden;
    container-name: task;
    container-type: inline-size;
}

p {
    white-space: nowrap; /* Don't forget this one */
    text-overflow: ellipsis;
}

.task img {
    min-width: 32px;
    min-height: 32px;
    height: 32px;
    width: 32px;
    object-fit: contain;
}

.task.active {
    background: #1b51b8;
    box-shadow: 2px 2px 2px 0 #08296f inset, -2px -2px 2px 0 #08296f inset;
}
.clock {
    justify-self: flex-end;
    background: #0f8fe9;
    border-left: 2px solid #1A3655;
    padding-inline: 2rem;
    height: 100%;
    font-size: 32px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 5px 5px 10px 0 #22BCE6 inset, -5px -5px 10px 0 #0d7d9f inset, 2px 2px 2px 0 #22BCE6 inset, -2px -2px 2px 0 #12819f inset;
}
@media screen and (max-width: 768px) {
    .start {
        padding-inline: 0.5rem;
    }
    .task {
        padding: 0.5rem;
    }
    .clock {
        padding-inline: 0.5rem;
    }
}

@container task (max-width: 48px) {
    .task p {
        display: none;
    }
}

.start-menu {
    width: fit-content;
    background-color: #0E60CB;
    position: absolute;
    bottom: 60px;
    left: 0;
    border-top-right-radius: 0.5rem;
    border-top-left-radius: 0.5rem;
    box-shadow: 0 10px 5px 5px rgba(0, 0, 0, 0.5), 0 6px 6px 0px #66A2E7 inset, -4px 0px 4px 0px #10429e inset;
    overflow: hidden;
    display: none;
}

.start-menu.active {
    display: block;
    z-index: 100;
    transition: transform 0.5s ease-in-out, z-index 0.1s 0s;
}

.user-profile img {
    width: 64px;
    height: 64px;
    border-radius: 0.2rem;
    border: 2px solid white;
    box-shadow: 2px 2px 4px 2px rgba(0,0,0,0.3);
}
.user-profile {
    padding: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 5px -10px 15px 0 #4792EC inset;
}
.user-profile h4 {
    color: white;
    font-size: clamp(24px, 1rem + 1vw, 36px);
    font-family: 'WinXp';

}
.quick-acces {
    padding-inline: 0.2rem;
}

.top-line {
    width: 100%;
    height: 4px;
    background: linear-gradient(to right, #fff, #F88A24, #B9C6D8);
}
.quick-acces-programs-wrapper {
    background: white;
    display: grid;
    grid-template-columns: 1fr 1fr;
    width: fit-content;
    height: fit-content;
    align-items: stretch;
}
.main-programs {
    padding: 1rem;
    display: flex;
    gap: 1rem;
    flex-direction: column;
    height: 100%;
}

.main-programs .program, .other-programs .program {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    width: 250px;
}
.main-programs .icon{
    width: 48px;
    height: 48px;
    object-fit: contain;
}
.other-programs .icon {
    width: 36px;
    height: 36px;
    object-fit: contain;
}
.main-programs .details {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}
.main-programs h3, .other-programs h3 {
    font-family: 'WinXp';
    font-size: clamp(22px, 0.8rem + 1vw, 28px);
    color: black;
    line-height: 80%;
}
.main-programs h4 {
    font-family: 'WinXp';
    font-size: clamp(18px, 0.7rem + 0.8vw, 24px);
    color: #808080;
    line-height: 80%;
    text-wrap: wrap;
}
.other-programs {
    width: 100%;
    height: 100%;
    background-color: #D3E5FA;
    border-left: 2px solid #95BDEE;
    display: flex;
    flex-direction: column;
    padding: 1rem;
    gap: 1rem;
}
.line_divider {
    width: 100%;
    height: 2px;
    background: linear-gradient(to right, transparent, rgba(0,0,0,0.25), transparent);
}
.main-programs .all-programs {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: clamp(22px, 0.8rem + 1vw, 28px);
    color: black;
    justify-content: center;
}
.all-programs img {
    width: 36px;
    height: 36px;
    object-fit: contain;
}
.power-options {
    padding: 1rem;
    display: flex;
    justify-content: flex-end;
    gap: 2rem;
    box-shadow: 0px 25px  30px 0 #438BE3 inset;
    border-top: 2px solid #1B69CE;
}
.power-options .option {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    font-size: clamp(18px, 0.7rem + 0.8vw, 24px);
    font-family: 'WinXp';
    color: white;
}
.power-options .option img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}
</style>
