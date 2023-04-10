<template>
    <div class="o-form --todo">
        <form @submit.prevent="handleFormSubmit">
            <div class="o-form__group">
                <label for="title">Title</label>
                <input type="text" id="title" v-model="dto.title" />
            </div>
            <div class="o-form__group">
                <label for="description">Description</label>
                <input type="text" id="description" v-model="dto.description" />
            </div>
            <div class="o-form__buttons">
                <button @click.prevent="handleClearClick" type="button">Clear</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</template>

<script>
import useTodo from "@/Domains/Todo/Composable/use-todo";
import {TodoDTO} from "@/Domains/Todo/Models/TodoDTO";
import { ref } from "vue";

export default {
    name: "OrganismTodoForm",
    setup() {
        const dto = ref(new TodoDTO(null, "", false))

        const { createTodo } = useTodo();

        const handleClearClick = () => {
            const confirmResults = window.confirm("Are you sure you want to clear the form ?");

            if(confirmResults) {
                clearDTO();
            }
        }

        const clearDTO = () => {
            dto.value.title = "";
            dto.value.description = "";
        }

        const handleFormSubmit = async () => {
            await createTodo(dto);

            clearDTO();
        }

        return {
            dto,
            handleClearClick,
            handleFormSubmit
        }
    }
}
</script>
