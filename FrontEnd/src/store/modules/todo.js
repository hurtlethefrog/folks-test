export const namespaced = true;

export const state = {
    todos: []
}

export const mutations = {
    SET_TODOS(state, dtos) {
        state.todos = dtos;
    },
    UPDATE_TODO(state, dto) {
        const index = state.todos.findIndex(item => item.id === dto.id);

        if(index !== -1) {
            state.todos.splice(index, 1, dto);
        }
    },
    ADD_TODO(state, dto) {
        state.todos.push(dto);
    },
    DELETE_TODO(state, dto) {
        const index = state.todos.findIndex(item => item.id === dto.id);

        if(index !== -1) {
            state.todos.splice(index, 1);
        }
    }
}

export const actions = {
    setTodos({ commit }, dtos) {
        commit('SET_TODOS', dtos);
    },
    addTodo({ commit }, dto) {
        commit('ADD_TODO', dto);
    },
    updateTodo({ commit }, dto) {
        commit('UPDATE_TODO', dto)
    },
    deleteTodo({ commit }, dto) {
        commit('DELETE_TODO', dto);
    }
}

export const getters = {
    all(state) {
        return state.todos;
    },
    getById: (state) => id => {
        return state.todos.find(item => item.id === id);
    }
}
