// @flow
import * as React from 'react';
import {FormControl, FormControlLabel, FormLabel, Radio, RadioGroup, TextField} from "@material-ui/core";
import {FormProps, register as utilRegister, RegisterFields} from "../../util/form";
import {CastMemberTypeMap} from "./PageList";

const registerFields: RegisterFields[] = [
    {
        name: "name"
    },
    {
        name: "type"
    }
]

const Form: React.FC<FormProps> = ({register, setValue, errors}) => {
    const fieldRegister = utilRegister(register, registerFields);
    const [memberType, setMemberType] = React.useState<string>("");

    const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
        setMemberType(event.target.value as string);
        setValue("type", event.target.value);
    };

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
            <FormControl margin={"normal"} component="fieldset">
                <FormLabel component="legend">Tipo</FormLabel>
                <RadioGroup
                    ref={fieldRegister["type"]}
                    aria-label={"type"}
                    name={"type"}
                    value={memberType}
                    onChange={handleChange}
                >
                    {
                        Object.keys(CastMemberTypeMap).map((value: any) => {
                            return <FormControlLabel
                                label={CastMemberTypeMap[value]}
                                value={value}
                                control={<Radio color={"primary"}/>}
                            />
                        })
                    }
                </RadioGroup>
            </FormControl>
        </React.Fragment>
    );
};

export default Form;