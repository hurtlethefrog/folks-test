export class TodoDTO {
    constructor(id, title, done) {
        this.id = id;
        this.title = title;
        this.done = done

        this.description = "";
        this.creation_date = new Date();
    }
}
