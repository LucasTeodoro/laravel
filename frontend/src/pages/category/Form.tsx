// @flow
import * as React from 'react';
import {Checkbox, TextField} from "@material-ui/core";
import {UseFormRegister, UseFormSetValue} from "react-hook-form/dist/types/form";
import {useEffect} from "react";
import {register, RegisterFields} from "../../util/form";

interface Props {
    setValue: UseFormSetValue<any>
    register: UseFormRegister<any>
}

const registerFields: RegisterFields[] = [
    {
        name: "name",
        options: {
            value: "",
            validate: (value) => value !== "" || "Este campo é requirido"
        }
    },
    {
        name: "description"
    },
    {
        name: "is_active"
    }
]

const Form: React.FC<Props> = (props) => {
    useEffect(() => {
        register(props.register, registerFields);
    }, [props]);

    return (
        <React.Fragment>
            <TextField
                name={"name"}
                label={"Nome"}
                variant={"outlined"}
                fullWidth
                onChange={(e) => props.setValue("name", e.target.value)}
            />
            <TextField
                name={"description"}
                label={"Descrição"}
                variant={"outlined"}
                rows={4}
                margin={"normal"}
                fullWidth
                multiline
                onChange={(e) => props.setValue("description", e.target.value)}
            />
            <Checkbox
                name="is_active"
                onChange={(e) => props.setValue("is_active", e.target.checked)}
                defaultChecked
            />
            Ativo?
        </React.Fragment>
    );
};

export default Form;