// @flow
import * as React from 'react';
import {FormControl, InputLabel, Select, TextField} from "@material-ui/core";
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
            <FormControl
                fullWidth
                margin={"normal"}
                variant="outlined"
            >
                <InputLabel id="select-name">Tipo</InputLabel>
                <Select
                    native
                    label="type"
                    labelId={"select-name"}
                    variant={"outlined"}
                    value={memberType}
                    onChange={handleChange}
                    inputProps={{
                        name: 'type',
                        id: 'select-name',
                    }}
                >
                    <option aria-label="None" value="" />
                    {
                        Object.keys(CastMemberTypeMap).map((value: any) => {
                           return <option key={value} value={value}>{CastMemberTypeMap[value]}</option>
                        })
                    }
                </Select>
            </FormControl>
        </React.Fragment>
    );
};

export default Form;