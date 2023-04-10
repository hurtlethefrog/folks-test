import {useStore} from "vuex";
import {computed} from "vue";
import useApi from "@/Domains/Core/Composable/use-api";
import TodoDTOFactory from "@/Domains/Todo/Factories/TodoDtoFactory";

export default function useTodo() {
    const store = useStore();
    const api = useApi();

    const updateTodo = async (item) => {
        const data = await api.put(`todos/${item.id}`, item);

        const dto = TodoDTOFactory(data);

        await store.dispatch("todos/updateTodo", dto);

        return dto;
    }

    const createTodo = async (item) => {
        const data = await api.post(`todos`, item);

        const dto = TodoDTOFactory(data);


        await store.dispatch("todos/addTodo", dto);

        return dto;
    }

    const fetchTodos = async () => {
        const data = await api.get(`todos`);

        const dtos = data.map(item => TodoDTOFactory(item));

        await store.dispatch("todos/setTodos", dtos);

        return dtos;
    }

    const deleteTodo = async (item) => {
        await api.destroy(`todos/${item.id}`);

        await store.dispatch("todos/deleteTodo", item);
    }

    return {
        todos: computed(() => store.getters['todos/all']),
        todoLoading: computed(() => store.getters['todos/isLoading']),
        fetchTodos,
        updateTodo,
        createTodo,
        deleteTodo
    }
}
