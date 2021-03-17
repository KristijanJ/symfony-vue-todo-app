<template>
    <div v-if="showModal">
        <div class="absolute bg-black bg-opacity-30 h-screen left-0 top-0 w-screen" @click="selectTodo(null, '')"></div>
        <div class="absolute bg-white left-1/2 p-6 rounded shadow-2xl top-1/2 transform-center w-6/12">
            <slot name="content"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        showModal: Boolean,
        selectTodo: Function
    }
}
</script>

<style>

</style>