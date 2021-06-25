// @flow
import * as React from 'react';
import {Checkbox, FormControlLabel, MenuItem, TextField} from "@material-ui/core";
import {useEffect, useState} from "react";
import {register as utilRegister, RegisterFields, FormProps, getData} from "../../util/form";
import categoryHttp from "../../util/http/category-http";
import {useFormContext, useFormState} from "react-hook-form";
import {useSnackbar} from "notistack";

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

const Form: React.FC<FormProps> = ({loading}) => {
    const {setValue, register, watch} = useFormContext();
    const {errors} = useFormState();
    const snackbar = useSnackbar();
    const [categoriesOptions, setCategoriesOptions] = React.useState<string[]>([]);
    const [isSubscribed, setIsSubscribed] = useState(true);
    const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
        setValue("categories_id", event.target.value as string[]);
    };
    const fieldRegister = utilRegister(register, registerFields);
    useEffect(() => {
        getData({
            httpProvider: categoryHttp,
            setData: setCategoriesOptions,
            snackbar,
            isSubscribed: () => {
                return isSubscribed;
            }
        });

        return () => {
            setIsSubscribed(false);
        }
    }, []);

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
            <TextField
                inputRef={fieldRegister["categories_id"].ref}
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
                disabled={loading}
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