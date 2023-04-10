<template>
    <ul class="o-list --todo">
        <MoleculeTodoListItem v-for="todo in todos" :key="todo.id" :item="todo"/>
    </ul>
</template>

<script>
import useTodo from "@/Domains/Todo/Composable/use-todo";
import MoleculeTodoListItem from "@/components/02_molecules/Domains/Todo/MoleculeTodoListItem";

export default {
    name: "OrganismTodoList",
    components: {MoleculeTodoListItem},
    async setup() {
        const { fetchTodos, todos } = useTodo();

        await fetchTodos();

        return {
            todos
        }
    }
}
</script>
