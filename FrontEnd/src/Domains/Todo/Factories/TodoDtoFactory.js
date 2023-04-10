import {TodoDTO} from "@/Domains/Todo/Models/TodoDTO";

export default function TodoDTOFactory(data) {
    if(!['id', 'title', 'done'].every(prop => prop in data)) {
        throw new Error("Missing props for factory")
    }

    const dto = new TodoDTO(data['id'], data['title'], data['done']);

    if('description' in data && data['description']) {
        dto.description = data['description'];
    }

    if('creation_date' in data && data['creation_date']) {
        dto.creation_date = data['creation_date'];
    }

    return dto;
}
