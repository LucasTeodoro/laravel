// @flow
import * as React from 'react';
import {Checkbox, MenuItem, TextField} from "@material-ui/core";
import {UseFormRegister, UseFormSetValue, UseFormWatch} from "react-hook-form/dist/types/form";
import {useEffect} from "react";
import {register as utilRegister, RegisterFields} from "../../util/form";
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

const Form: React.FC<Props> = ({setValue, register, watch}) => {
    const [categoriesOptions, setCategoriesOptions] = React.useState<string[]>([]);
    const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
        setValue("categories_id", event.target.value as string[], {
            shouldValidate: true
        });
    };
    useEffect(() => {
        utilRegister(register, registerFields);
    }, [register, setValue]);


    useEffect(() => {
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
                onChange={(e) => setValue("name", e.target.value)}
            />
            <TextField
                select
                label="Categorias"
                name="categories_id"
                value={watch('categories_id')}
                onChange={handleChange}
                fullWidth
                margin={'normal'}
                variant={"outlined"}
                SelectProps={{
                    multiple: true
                }}
            >
                <MenuItem value="" disabled><em>Selecione categorias</em></MenuItem>;
                {
                    categoriesOptions.map((value: any) => {
                        return <MenuItem key={value.id} value={value.id}>{value.name}</MenuItem>;
                    })
                }
            </TextField>
            <Checkbox
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