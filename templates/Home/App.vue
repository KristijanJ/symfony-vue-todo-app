<template>
    <div v-if="loading" class="flex items-center justify-center min-h-screen">Loading...</div>

    <div v-else class="flex flex-col min-h-screen justify-between">
        <div class="bg-green-200 h-16 mb-10 shadow shadow-sm text-center flex items-center justify-center">
            <h1 class="text-3xl">Todo App</h1>
        </div>
        <div class="container mx-auto">
            <div class="flex justify-center text-center items-center mb-10">
                <span class="cursor-pointer"><i class="fas fa-plus-circle mr-1"></i> Add new</span>
            </div>
            <div class="flex flex-col items-center">
                <div v-for="todo in todos" :key="todo.id" class="w-1/2 m-3 p-4 flex shadow shadow-sm text-gray-700">
                    <div class="flex-1 pr-4">{{ todo.title }}</div>
                    <div class="flex items-center">
                        <i class="far fa-edit mr-2 cursor-pointer"></i>
                        <i class="far fa-trash-alt cursor-pointer"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-green-200 h-16 mt-10 shadow shadow-sm text-center flex items-center justify-center"></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            todos: []
        }
    },
    mounted() {
        this.getTodos();
    },
    methods: {
        getTodos() {
            fetch('http://127.0.0.1:8000/api/get_all_todos')
                .then(response => response.json())
                .then(data => {
                    this.todos = data;
                    this.loading = false;
                });
        }
    }
};
</script>