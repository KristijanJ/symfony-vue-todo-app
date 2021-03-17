<template>
    <div class="flex flex-col items-center">
        <draggable v-model="computedTodos" v-bind="dragOptions" class="list-width">
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
            return { animation: 1, group: 'description', disabled: false, ghostClass: 'ghost-class', handle: '.draggable-handler' };
        },
    }
}
</script>

<style scoped>
    .ghost-class {
        background: rgb(243, 244, 246);
        box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
    }

    @media (min-width: 640px) {
        .list-width {
            width: 80vw;
        }
    }

    @media (min-width: 1024px) {
        .list-width {
            width: 60vw;
        }
    }

    @media (min-width: 1440px) {
        .list-width {
            width: 40vw;
        }
    }
</style>