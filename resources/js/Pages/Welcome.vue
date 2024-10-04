<script>
import { Link } from "@inertiajs/vue3"

export default {
    name: "Welcome",
    components: {
        Link
    }
}
</script>

<template>
    <div class="welcome-wrapper">
        <header>
            <img src="Microsoft_Windows_XP_Logo.svg" alt="Microsoft Windows XP" />
        </header>
        <div id="top-line"></div>
        <main>
            <section class="welcome-message">
                <h2>Witaj w moim portfolio</h2>
                <p>Dziękuję za zainteresownia i zapraszam do zapozanania się z portfolio oraz zabawy w symulacji kultowego już systemu windows xp.</p>
                <p>Znajdą tu państwo dane kontaktowe oraz moje realizacjie zachęcam również do zapoznania sie z blogiem schowany w przeglądarce internet explorer</p>
            </section>
        </main>
        <div id="bot-line"></div>
        <footer>
            <Link :href="route('homepage')">
                Przejdź dalej
                <svg id="next-arrow" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 115.4 122.88" fill="#fff"><title>up-arrow</title><path d="M24.94,67.88A14.66,14.66,0,0,1,4.38,47L47.83,4.21a14.66,14.66,0,0,1,20.56,0L111,46.15A14.66,14.66,0,0,1,90.46,67.06l-18-17.69-.29,59.17c-.1,19.28-29.42,19-29.33-.25L43.14,50,24.94,67.88Z"/></svg>
            </Link>
        </footer>
    </div>
</template>

<style scoped>
h2 {
    font-size: clamp(48px, 2rem + 2vw, 96px);
    font-weight: 900;
    color: white;
    font-family: bio-sans, sans-serif;
    font-weight: bold;
    text-shadow: 4px 4px 8px #1342A5;
}

header, footer {
    width: 100%;
    height: 100px;
    background: #003399;
    display: flex;
    padding-inline: 3rem;
    align-items: center;
}
footer {
    justify-content: flex-end;
}
header img {
    height: 60px;
}
.welcome-wrapper {
    border: 2px solid black;
    font-family: "WinXp";
    height: 100vh;
    display: flex;
    flex-direction: column;
    gap: 0;
    justify-content: center;
}
a {
    display: flex;
    gap: 1rem;
    font-size: 24px;
    align-items: center;
    color: white;
}
svg{
    width: 36px;
    height: 36px;
    padding: 0.2rem;
    transform: rotate(90deg);
    background: #41A63E;
    border: 1px solid white;
    border-radius: 0.2rem;
    box-shadow: 3px -3px 6px 0 #9FCF9D inset, -3px 3px 4px 0 #40813F inset;
}
a:first-letter {
    text-decoration: underline;
}
main {
    width: 100%;
    height: 100%;
    background: linear-gradient(-35deg, #7394E3 15%, #4E6FD6 50%, #6C8FE1 85%);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
#top-line {
    background: linear-gradient(90deg, #5B7EDC, #C2D8FD, #5B7EDC);
    width: 100%;
    height: 6px;
    margin: 0;
    box-sizing: border-box;
}
#bot-line {
    background: linear-gradient(-90deg, #023399 20%, #F2943A 65%, #0B3CA2);
    width: 100%;
    height: 6px;
    margin: 0;
    box-sizing: border-box;
}
.welcome-message {
    padding: 2rem 8rem;
    display: flex;
    gap: 2rem;
    flex-direction: column;
}
.welcome-message p {
    font-size: clamp(18px, 0.8rem + 1vw, 24px);
    color: white;
}
@media screen and (max-width: 1024px) {
    .welcome-message {
        padding: 2rem 4rem;
    }
}
@media screen and (max-width: 768px) {
    .welcome-message {
        padding: 2rem;
    }
    header, footer {
        padding-inline: 1rem;
    }
}
</style>
