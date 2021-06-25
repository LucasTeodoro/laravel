// @flow
import * as React from 'react';
import DefaultForm from '../../components/DefaultPageForm';
import Form from "./Form";
import {UseFormProps} from "react-hook-form";
import categoryHttp from "../../util/http/category-http";
import {yupResolver} from "@hookform/resolvers/yup";
import {CategorySchema} from "../../util/vendor/yup";
import {useHistory, useParams} from 'react-router-dom';
import {useEffect, useState} from "react";
import {getData, Submit} from "../../util/form";
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
    const snackbar = useSnackbar();
    const history = useHistory();
    const [category, setCategory] = useState<{id: string} | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    const [isSubscribed, setIsSubscribed] = useState(true);
    useEffect(() => {
        if(!id) return;
        getData({
            httpProvider,
            setValue: setLoading,
            setData: setCategory,
            id,
            snackbar,
            isSubscribed: () => {return isSubscribed}
        });

        return () => {
            setIsSubscribed(false);
        };
    }, [id]);
    async function onSubmit(formData: any, event: any) {
        await Submit(
            httpProvider,
            formData,
            url,
            setLoading,
            event,
            category,
            snackbar,
            history,
            "Categoria salva com sucesso.",
            "Não foi possivel salvar a categoria."
        );
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