import {RegisterOptions} from "react-hook-form/dist/types/validator";
import {UseFormRegister} from "react-hook-form/dist/types/form";


export interface FormProps {
    loading?: boolean
}

export interface RegisterFields {
    name: string,
    options?: RegisterOptions
}

export function register(formRegister: UseFormRegister<any>, fields: RegisterFields[]) {
    const registries = {};
    fields.forEach((field) => {
        registries[field.name] = formRegister(field.name, field.options);
    });

    return registries;
}