<script>
export default {
    name: "Desktop"
}
</script>

<template>
<main class="desktop-wrapper">

</main>
</template>

<style scoped>
.desktop-wrapper {
    width: 100%;
    height: calc(100vh - 60px);
    display: grid;
    grid-template-columns: repeat(auto-fit, 96px);
    grid-template-rows: repeat(auto-fit, 96px);
    gap: 1rem;
}
.test {
    background-color: red;
    width: 100%;
    height: 100%;
}
</style>
