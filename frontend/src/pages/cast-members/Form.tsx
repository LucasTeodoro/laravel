// @flow
import * as React from 'react';
import {
    FormControl,
    FormControlLabel,
    FormHelperText,
    FormLabel,
    Radio,
    RadioGroup,
    TextField
} from "@material-ui/core";
import {FormProps, register as utilRegister, RegisterFields} from "../../util/form";
import {CastMemberTypeMap} from "./PageList";
import {useFormContext, useFormState} from "react-hook-form";

const registerFields: RegisterFields[] = [
    {
        name: "name"
    },
    {
        name: "type"
    }
]

const Form: React.FC<FormProps> = ({loading}) => {
    const {register, watch} = useFormContext();
    const {errors} = useFormState();
    const fieldRegister = utilRegister(register, registerFields);

    return (
        <React.Fragment>
            <TextField
                inputRef={fieldRegister["name"].ref}
                name={"name"}
                label={"Nome"}
                variant={"outlined"}
                fullWidth
                onChange={fieldRegister["name"].onChange}
                disabled={loading}
                error={errors.name !== undefined}
                helperText={errors.name && errors.name.message}
                InputLabelProps={{shrink: !!watch("name")}}
            />
            <FormControl
                margin={"normal"}
                error={errors.type !== undefined}
                component="fieldset"
            >
                <FormLabel component="legend">Tipo</FormLabel>
                <RadioGroup
                    ref={fieldRegister["type"].ref}
                    aria-label={"type"}
                    name={"type"}
                    value={watch("type") + ""}
                    onChange={fieldRegister["type"].onChange}
                >
                    {
                        Object.keys(CastMemberTypeMap).map((value: any) => {
                            return <FormControlLabel
                                key={value}
                                label={CastMemberTypeMap[value]}
                                value={value}
                                control={<Radio color={"primary"}/>}
                            />
                        })
                    }
                </RadioGroup>
                {
                    errors.type && <FormHelperText id={"type-helper-text"}>{errors.type.mesage}</FormHelperText>
                }
            </FormControl>
        </React.Fragment>
    );
};

export default Form;