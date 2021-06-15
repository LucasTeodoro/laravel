import {RegisterOptions} from "react-hook-form/dist/types/validator";
import {UseFormRegister, UseFormSetValue, UseFormWatch} from "react-hook-form/dist/types/form";
import {FieldErrors} from "react-hook-form/dist/types/errors";


export interface FormProps {
    setValue: UseFormSetValue<any>
    register: UseFormRegister<any>
    watch?: UseFormWatch<any>
    errors: FieldErrors
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