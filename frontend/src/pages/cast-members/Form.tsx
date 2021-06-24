// @flow
import * as React from 'react';
import {FormControl, FormControlLabel, FormLabel, Radio, RadioGroup, TextField} from "@material-ui/core";
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
    const {register, setValue, watch, getValues} = useFormContext();
    const {errors} = useFormState();
    const fieldRegister = utilRegister(register, registerFields);
    const [memberType, setMemberType] = React.useState<string>("");

    const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
        setMemberType(event.target.value as string);
        setValue("type", event.target.value);
    };

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
            <FormControl margin={"normal"} component="fieldset">
                <FormLabel component="legend">Tipo</FormLabel>
                <RadioGroup
                    ref={fieldRegister["type"].ref}
                    aria-label={"type"}
                    name={"type"}
                    value={memberType}
                    onChange={handleChange}
                >
                    {
                        Object.keys(CastMemberTypeMap).map((value: any) => {
                            return <FormControlLabel
                                key={value}
                                label={CastMemberTypeMap[value]}
                                value={value}
                                control={<Radio checked={getValues("type") == value} color={"primary"}/>}
                            />
                        })
                    }
                </RadioGroup>
            </FormControl>
        </React.Fragment>
    );
};

export default Form;