// @flow
import * as React from 'react';
import {Checkbox, FormControl, Input, InputLabel, MenuItem, Select, TextField} from "@material-ui/core";
import {UseFormRegister, UseFormSetValue, UseFormWatch} from "react-hook-form/dist/types/form";
import {useEffect} from "react";
import {register, RegisterFields} from "../../util/form";
import categoryHttp from "../../util/http/category-http";

interface Props {
    setValue: UseFormSetValue<any>
    register: UseFormRegister<any>
    watch: UseFormWatch<any>
}

const registerFields: RegisterFields[] = [
    {
        name: "name",
        options: {
            validate: (value) => value !== "" || "Este campo é requirido"
        }
    },
    {
        name: "categories_id",
        options: {
            validate: (value) => value.length > 0 || "Este campo é requirido"
        }
    },
    {
        name: "is_active",
        options: {
            value: true
        }
    }
]

const Form: React.FC<Props> = (props) => {
    const [categoriesOptions, setCategoriesOptions] = React.useState<string[]>([]);
    const [categories, setCategories] = React.useState<string[]>([]);
    const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
        setCategories(event.target.value as string[]);
        props.setValue("categories_id", event.target.value as string[]);
    };
    useEffect(() => {
        register(props.register, registerFields);
    }, [props]);

    useEffect(() => {
        props.setValue("categories_id", []);
        categoryHttp.list().then(({data}) => {
            setCategoriesOptions(data);
        });
    }, []);

    return (
        <React.Fragment>
            <TextField
                name={"name"}
                label={"Nome"}
                variant={"outlined"}
                fullWidth
                onChange={(e) => props.setValue("name", e.target.value)}
            />
            <FormControl
                fullWidth
                margin={"normal"}
                variant={"outlined"}
            >
                <InputLabel id="select-name">Categorias</InputLabel>
                <Select
                    label="categories_id"
                    labelId={"select-name"}
                    value={categories}
                    multiple
                    onChange={handleChange}
                    input={<Input />}
                >
                    {
                        categoriesOptions.map((value: any) => {
                            return <MenuItem key={value.id} value={value.id}>{value.name}</MenuItem>;
                        })
                    }
                </Select>
            </FormControl>
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