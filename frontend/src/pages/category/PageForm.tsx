// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import {UseFormProps} from "react-hook-form";
import categoryHttp from "../../util/http/category-http";
import {yupResolver} from "@hookform/resolvers/yup";
import {CategorySchema} from "../../util/vendor/yup";
import {useHistory, useParams } from 'react-router-dom';
import {useEffect, useState} from "react";
import {useSnackbar} from "notistack";

const FormProps: UseFormProps = {
    resolver: yupResolver(CategorySchema),
    defaultValues: {
        is_active: true
    }
}

const url = 'categories';
const httpProvider = categoryHttp;

const PageForm = () => {
    const { id } = useParams<{ id: string }>();
    const history = useHistory();
    const snackbar = useSnackbar();
    const [category, setCategory] = useState<{id: string} | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    useEffect(() => {
        if(!id) return;
        setLoading(true);
        httpProvider.get(id)
            .then(({data}) => {
                setCategory(data);
            }).finally(() => setLoading(false));
    }, [id]);
    function onSubmit(formData: any, event: any) {
        setLoading(true);
        const http = !category
            ? httpProvider.create(formData)
            : httpProvider.update(category.id, formData);

            http
                .then(({data}) => {
                    snackbar.enqueueSnackbar("Categoria salva com sucesso.", {variant: "success"})
                    setTimeout(() => {
                        event ? (
                            category
                                ? history.replace(`/${url}/${data.id}/edit`)
                                : history.push(`/${url}/${data.id}/edit`)
                        ) : history.push(`/${url}`);
                    });
                })
                .catch(() => {
                    snackbar.enqueueSnackbar("Não foi possivel salvar a categoria.", {variant: "error"})
                })
                .finally(() => setLoading(false));
    }

    return (
        <DefaultForm
            onSubmit={onSubmit}
            pageTitle={!id ? "Adicionar categoria" : "Editar categoria"}
            Form={Form}
            formProps={FormProps}
            data={category}
            loading={loading}
        />
    );
};

export default PageForm;