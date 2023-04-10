<template>
    <li class="o-list-item --todo">
        <div class="texts">
            <h3>{{ item.title }}</h3>
            <p class="small">{{ item.description }}</p>
        </div>
        <div class="buttons">
            <button @click="handleDeleteClick">delete</button>
            <input type="checkbox" :checked="item.done" @change="updateStatus">
        </div>
    </li>
</template>

<script>
import {TodoDTO} from "@/Domains/Todo/Models/TodoDTO";
import {toRefs} from "vue";
import useTodo from "@/Domains/Todo/Composable/use-todo";

export default {
    name: "MoleculeTodoListItem",
    props: {
        item: {
            type: Object,
            required: true,
            validator(value) {
                return value instanceof TodoDTO
            }
        }
    },
    setup(props) {
        const { item } = toRefs(props);

        const { updateTodo, deleteTodo } = useTodo();

        const updateStatus = async () => {
            const itemClone = Object.assign({}, item.value);
            Object.setPrototypeOf(itemClone, TodoDTO);

            itemClone.done = true;

            await updateTodo(itemClone);
        }

        const handleDeleteClick = async () => {
            const confirm = window.confirm('deleting, are you sure ?');

            if(confirm) {
                await deleteTodo(item.value);
            }
        }

        return {
            updateStatus,
            handleDeleteClick
        }
    }
}
</script>
