<template>
    <template v-if="loading">
        <loading-screen></loading-screen>
    </template>

    <template v-else>
        <div class="flex flex-col min-h-screen justify-between">
            <the-header></the-header>
            <div class="container mx-auto">
                <div class="flex justify-center text-center items-center mb-10">
                    <span class="cursor-pointer" @click="addTodo"><i class="fas fa-plus-circle mr-1"></i> Add new</span>
                </div>
                <todo-list :todos="todos" :selectTodo="selectTodo" @update-todo-order="updateTodoOrder"></todo-list>
            </div>
            <the-footer></the-footer>
        </div>
    </template>

    <todo-modal :show-modal="showCreateModal" :select-todo="selectTodo">
        <template #content>
            <div class="flex flex-col">
                <label class="font-bold mb-3" for="title">Title: </label>
                <textarea type="text" name="title" id="title" rows="4" v-model="selectedTodo.title" class="border border-gray-300 rounded-sm p-2" placeholder="Enter todo..."></textarea>
            </div>
            <div class="mt-10 text-right text-gray-700">
                <button class="bg-green-200 mr-4 p-2 pt-1 pb-1 rounded hover:text-black" @click="createTodo">Create</button>
                <button class="bg-red-200 p-2 pt-1 pb-1 rounded hover:text-black" @click="selectTodo(null, '')">Cancel</button>
            </div>
        </template>
    </todo-modal>

    <todo-modal :show-modal="showEditModal" :select-todo="selectTodo">
        <template #content>
            <div class="flex flex-col">
                <label class="font-bold mb-3" for="title">Title: </label>
                <textarea type="text" name="title" id="title" rows="4" v-model="selectedTodo.title" class="border border-gray-300 rounded-sm p-2"></textarea>
            </div>
            <div class="mt-10 text-right text-gray-700">
                <button class="bg-green-200 mr-4 p-2 pt-1 pb-1 rounded hover:text-black" @click="updateTodo">Update</button>
                <button class="bg-red-200 p-2 pt-1 pb-1 rounded hover:text-black" @click="selectTodo(null, '')">Cancel</button>
            </div>
        </template>
    </todo-modal>

    <todo-modal :show-modal="showDeleteModal" :select-todo="selectTodo">
        <template #content>
            <div class="flex flex-col">
                <div class="font-bold text-center text-2x1">Are you sure you want to delete this todo?</div>
            </div>
            <div class="mt-10 text-right text-gray-700">
                <button class="bg-green-200 mr-4 p-2 pt-1 pb-1 rounded hover:text-black" @click="deleteTodo">Delete</button>
                <button class="bg-red-200 p-2 pt-1 pb-1 rounded hover:text-black" @click="selectTodo(null, '')">Cancel</button>
            </div>
        </template>
    </todo-modal>

</template>

<script>
import LoadingScreen from '../../Components/LoadingScreen';
import TheHeader from '../../Components/TheHeader';
import TheFooter from '../../Components/TheFooter';
import TodoList from '../../Components/TodoList';
import TodoModal from '../../Components/TodoModal';

export default {
    components: {
        'loading-screen': LoadingScreen,
        'todo-list': TodoList,
        'the-header': TheHeader,
        'the-footer': TheFooter,
        'todo-modal': TodoModal
    },
    data() {
        return {
            loading: true,
            todos: [],
            selectedTodo: null,
            showEditModal: false,
            showDeleteModal: false,
            showCreateModal: false
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
            this.showCreateModal = true;
            this.selectedTodo = {
                title: '',
                status: 1
            }
        },
        selectTodo(todo, action) {
            this.selectedTodo = todo;
            if (action === 'edit') {
                this.showEditModal = true;
            }
            if (action === 'delete') {
                this.showDeleteModal = true;
            }
            if (!action) {
                this.showCreateModal = false;
                this.showEditModal = false;
                this.showDeleteModal = false;
            }
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
                    this.showEditModal = false;
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
                    this.showDeleteModal = false;
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
                    this.showCreateModal = false;
                    this.selectedTodo = null;
                });
        },
        updateTodoOrder(newTodos) {
            console.log(newTodos);
            this.todos = newTodos;
        }
    }
};
</script>