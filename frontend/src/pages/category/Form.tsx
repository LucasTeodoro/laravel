// @flow
import * as React from 'react';
import {Checkbox, FormControlLabel, TextField} from "@material-ui/core";
import {FormProps, register as utilRegister, RegisterFields} from "../../util/form";
import {useFormContext, useFormState} from "react-hook-form";

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

const Form: React.FC<FormProps> = ({loading}) => {
    const {register, setValue, watch} = useFormContext();
    const { errors } = useFormState();
    const fieldRegister = utilRegister(register, registerFields);

    return (
        <React.Fragment>
            <TextField
                name={"name"}
                label={"Nome"}
                inputRef={fieldRegister["name"].ref}
                variant={"outlined"}
                fullWidth
                onChange={fieldRegister["name"].onChange}
                disabled={loading}
                error={errors.name !== undefined}
                helperText={errors.name && errors.name.message}
                InputLabelProps={{shrink: !!watch("name")}}
            />
            <TextField
                inputRef={fieldRegister["description"].ref}
                name={"description"}
                label={"Descrição"}
                variant={"outlined"}
                rows={4}
                margin={"normal"}
                fullWidth
                multiline
                onChange={fieldRegister["description"].onChange}
                disabled={loading}
                error={errors.description !== undefined}
                helperText={errors.description && errors.description.message}
                InputLabelProps={{shrink: !!watch("description")}}
            />
            <FormControlLabel
                disabled={loading}
                control={
                    <Checkbox
                        color={"primary"}
                        name="is_active"
                        onChange={(e) => setValue("is_active", e.target.checked)}
                        checked={watch("is_active")}
                        ref={fieldRegister["is_active"].ref}
                    />
                }
                label={"Ativo?"}
                labelPlacement={"end"}
            />
        </React.Fragment>
    );
};

export default Form;