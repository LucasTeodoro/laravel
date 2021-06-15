// @flow
import * as React from 'react';
import {Checkbox, MenuItem, TextField} from "@material-ui/core";
import {useEffect} from "react";
import {register as utilRegister, RegisterFields, FormProps} from "../../util/form";
import categoryHttp from "../../util/http/category-http";

const registerFields: RegisterFields[] = [
    {
        name: "name"
    },
    {
        name: "categories_id"
    },
    {
        name: "is_active"
    }
]

const Form: React.FC<FormProps> = ({setValue, register, watch, errors}) => {
    const [categoriesOptions, setCategoriesOptions] = React.useState<string[]>([]);
    const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
        setValue("categories_id", event.target.value as string[]);
    };
    const fieldRegister = utilRegister(register, registerFields);
    useEffect(() => {
        categoryHttp.list().then(({data}) => {
            setCategoriesOptions(data);
        });
    }, []);

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
                inputRef={fieldRegister["categories_id"]}
                select
                label="Categorias"
                name="categories_id"
                value={watch!('categories_id')}
                onChange={handleChange}
                fullWidth
                margin={'normal'}
                variant={"outlined"}
                SelectProps={{
                    multiple: true
                }}
                error={errors.categories_id !== undefined}
                helperText={errors.categories_id && errors.categories_id.message}
            >
                <MenuItem value="" disabled><em>Selecione categorias</em></MenuItem>;
                {
                    categoriesOptions.map((value: any) => {
                        return <MenuItem key={value.id} value={value.id}>{value.name}</MenuItem>;
                    })
                }
            </TextField>
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