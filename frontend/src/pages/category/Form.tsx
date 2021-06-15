// @flow
import * as React from 'react';
import {Checkbox, TextField} from "@material-ui/core";
import {FormProps, register as utilRegister, RegisterFields} from "../../util/form";

const registerFields: RegisterFields[] = [
    {
        name: "name"
    },
    {
        name: "description"
    },
    {
        name: "is_active"
    }
]

const Form: React.FC<FormProps> = ({register, setValue, errors}) => {
    const fieldRegister = utilRegister(register, registerFields);

    return (
        <React.Fragment>
            <TextField
                inputRef={fieldRegister["name"]}
                name={"name"}
                label={"Nome"}
                variant={"outlined"}
                fullWidth
                onChange={(e) => setValue("name", e.target.value)}
                error={errors.name !== undefined}
                helperText={errors.name && errors.name.message}
            />
            <TextField
                inputRef={fieldRegister["description"]}
                name={"description"}
                label={"Descrição"}
                variant={"outlined"}
                rows={4}
                margin={"normal"}
                fullWidth
                multiline
                onChange={(e) => setValue("description", e.target.value)}
                error={errors.description !== undefined}
                helperText={errors.description && errors.description.message}
            />
            <Checkbox
                ref={fieldRegister["is_active"]}
                color={"primary"}
                name="is_active"
                onChange={(e) => setValue("is_active", e.target.checked)}
                defaultChecked
            />
            Ativo?
        </React.Fragment>
    );
};

export default Form;