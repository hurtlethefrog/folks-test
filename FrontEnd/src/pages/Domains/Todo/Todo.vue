<template>
    <div class="p-todo">
        <h1>My todos</h1>
        <div class="p-todo__list">
            <suspense>
                <template #default>
                    <OrganismTodoList />
                </template>
                <template #fallback>
                    Todos are loading...
                </template>
            </suspense>
        </div>

        <h1>Add a todo</h1>
        <OrganismTodoForm />
    </div>
</template>

<script>
import OrganismTodoList from "@/components/03_Organisms/Domains/Todo/OrganismTodoList";
import OrganismTodoForm from "@/components/03_Organisms/Domains/Todo/OrganismTodoForm";
export default {
    name: "Todo",
    components: {OrganismTodoList, OrganismTodoForm}
}
</script>
