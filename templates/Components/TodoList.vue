<template>
    <div class="flex flex-col items-center">
        <draggable v-model="computedTodos" v-bind="dragOptions" @change="draggableLog" class="w-1/2">
            <div v-for="todo in todos" :key="todo.id" class="m-3 p-4 flex shadow shadow-sm text-gray-700">
                <div class="flex-1 mr-4">{{ todo.title }}</div>
                <div class="flex items-center">
                    <i class="far fa-edit mr-2 cursor-pointer" @click="selectTodo(todo, 'edit')"></i>
                    <i class="far fa-trash-alt mr-2 cursor-pointer" @click="selectTodo(todo, 'delete')"></i>
                    <i class="fas fa-arrows-alt cursor-move draggable-handler"></i>
                </div>
            </div>
        </draggable>
    </div>
</template>

<script>
import { VueDraggableNext } from 'vue-draggable-next';

export default {
    props: {
        todos: Array,
        selectTodo: Function
    },
    components: {
        draggable: VueDraggableNext
    },
    computed: {
        computedTodos: {
            get: function () {
                return this.todos;
            },
            set: function (newTodos) {
                this.$emit('update-todo-order', newTodos);
            }
        },
        dragOptions: function() {
            return { animation: 1, group: 'description', disabled: false, ghostClass: 'bg-gray-light', handle: '.draggable-handler' };
        },
    },
    methods: {
        draggableLog: function(event) {
            if (event.moved) {
                console.log('moved');
            }
        }
    }
}
</script>

<style>

</style>