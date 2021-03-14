<template>
    <div v-if="loading" class="flex items-center justify-center min-h-screen">Loading...</div>

    <div v-else class="flex flex-col min-h-screen justify-between">
        <div class="bg-green-200 h-16 mb-10 shadow shadow-sm text-center flex items-center justify-center">
            <h1 class="text-3xl">Todo App</h1>
        </div>
        <div class="container mx-auto">
            <div class="flex justify-center text-center items-center mb-10">
                <span class="cursor-pointer" @click="addTodo"><i class="fas fa-plus-circle mr-1"></i> Add new</span>
            </div>
            <div class="flex flex-col items-center">
                <div v-for="todo in todos" :key="todo.id" class="w-1/2 m-3 p-4 flex shadow shadow-sm text-gray-700">
                    <div class="flex-1 mr-4">{{ todo.title }}</div>
                    <div class="flex items-center">
                        <i class="far fa-edit mr-2 cursor-pointer" @click="selectTodo(todo, 'edit')"></i>
                        <i class="far fa-trash-alt cursor-pointer" @click="selectTodo(todo, 'delete')"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-green-200 h-16 mt-10 shadow shadow-sm text-center flex items-center justify-center"></div>
    </div>

    <template v-if="selectedTodo">
        <div  class="absolute bg-black bg-opacity-30 h-screen left-0 top-0 w-screen" @click="selectTodo(null, '')"></div>
        <div class="absolute bg-white left-1/2 p-6 rounded shadow-2xl top-1/2 transform-center w-6/12">
            <div v-if="action === 'edit' || action === 'create'" class="flex flex-col">
                <label class="font-bold mb-3" for="title">Title: </label>
                <textarea type="text" name="title" id="title" rows="4" v-model="this.selectedTodo.title" class="border border-gray-300 rounded-sm p-2"></textarea>
            </div>

            <div v-if="action === 'delete'" class="flex flex-col">
                <div class="font-bold text-center text-2x1">Are you sure you want to delete this todo?</div>
            </div>
            
            <div class="mt-10 text-right text-gray-700">
                <button v-if="action === 'create'" class="bg-green-200 mr-4 p-2 pt-1 pb-1 rounded hover:text-black" @click="createTodo">Create</button>
                <button v-if="action === 'edit'" class="bg-green-200 mr-4 p-2 pt-1 pb-1 rounded hover:text-black" @click="updateTodo">Update</button>
                <button v-if="action === 'delete'" class="bg-green-200 mr-4 p-2 pt-1 pb-1 rounded hover:text-black" @click="deleteTodo">Delete</button>
                <button class="bg-red-200 p-2 pt-1 pb-1 rounded hover:text-black" @click="selectTodo(null, '')">Cancel</button>
            </div>
        </div>
    </template>

</template>

<script>
export default {
    data() {
        return {
            loading: true,
            todos: [],
            selectedTodo: null,
            action: ''
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
        },
        addTodo() {
            this.action = 'create';
            this.selectedTodo = {
                title: 'New todo',
                status: 1
            }
        },
        selectTodo(todo, action) {
            this.selectedTodo = todo;
            this.action = action;
        },
        updateTodo() {
            fetch('http://127.0.0.1:8000/api/update-todo/' + this.selectedTodo.id, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(this.selectedTodo)
            })
                .then(response => response.json())
                .then(() => {
                    this.getTodos();
                    this.selectedTodo = null;
                });
        },
        deleteTodo() {
            fetch('http://127.0.0.1:8000/api/delete-todo/' + this.selectedTodo.id, {
                method: 'DELETE'
            })
                .then(response => response.json())
                .then(() => {
                    this.getTodos();
                    this.selectedTodo = null;
                });
        },
        createTodo() {
            fetch('http://127.0.0.1:8000/api/create-todo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(this.selectedTodo)
            })
                .then(response => response.json())
                .then(() => {
                    this.getTodos();
                    this.selectedTodo = null;
                });
        },
    }
};
</script>