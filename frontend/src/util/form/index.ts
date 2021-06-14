import {RegisterOptions} from "react-hook-form/dist/types/validator";
import {UseFormRegister} from "react-hook-form/dist/types/form";


export interface RegisterFields {
    name: string,
    options?: RegisterOptions
}

export function register(formRegister: UseFormRegister<any>, fields: RegisterFields[]) {
    fields.forEach((field) => {
        formRegister(field.name, field.options);
    });
}