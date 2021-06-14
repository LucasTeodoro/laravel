// @flow
import * as React from 'react';
import {FormControl, FormControlLabel, FormLabel, Radio, RadioGroup, TextField} from "@material-ui/core";
import {UseFormRegister, UseFormSetValue} from "react-hook-form/dist/types/form";
import {useEffect} from "react";
import {register as utilRegister, RegisterFields} from "../../util/form";
import {CastMemberTypeMap} from "./PageList";

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
        name: "type",
        options: {
            value: "",
            validate: (value) => value !== "" || "Este campo é requirido",
        }
    }
]

const Form: React.FC<Props> = ({register, setValue}) => {
    useEffect(() => {
        utilRegister(register, registerFields);
    }, [register]);

    const [memberType, setMemberType] = React.useState<string>("");

    const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
        setMemberType(event.target.value as string);
        setValue("type", event.target.value);
    };

    return (
        <React.Fragment>
            <TextField
                name={"name"}
                label={"Nome"}
                variant={"outlined"}
                fullWidth
                onChange={(e) => setValue("name", e.target.value)}
            />
            <FormControl margin={"normal"} component="fieldset">
                <FormLabel component="legend">Tipo</FormLabel>
                <RadioGroup
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