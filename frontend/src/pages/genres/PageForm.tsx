// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import genreHttp from "../../util/http/genre-http";
import {UseFormProps} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import {GenreSchema} from "../../util/vendor/yup";
import {useHistory, useParams} from "react-router-dom";
import {useSnackbar} from "notistack";
import {useEffect, useState} from "react";

const FormProps: UseFormProps = {
    resolver: yupResolver(GenreSchema),
    defaultValues: {
        categories_id: [],
        is_active: true
    }
}

const url = 'genres';
const httpProvider = genreHttp;

const PageForm = () => {
    const { id } = useParams<{ id: string }>();
    const history = useHistory();
    const snackbar = useSnackbar();
    const [genre, setGenre] = useState<{id: string} | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    useEffect(() => {
        if(!id) return;
        setLoading(true);
        httpProvider.get(id)
            .then(({data}) => {
                setGenre({categories_id: [], ...data});
            }).finally(() => setLoading(false));
    }, [id]);
    function onSubmit(formData: any, event: any) {
        setLoading(true);
        const http = !genre
            ? httpProvider.create(formData)
            : httpProvider.update(genre.id, formData);

        http
            .then(({data}) => {
                snackbar.enqueueSnackbar("Gênero salvo com sucesso.", {variant: "success"})
                setTimeout(() => {
                    event ? (
                        genre
                            ? history.replace(`/${url}/${data.id}/edit`)
                            : history.push(`/${url}/${data.id}/edit`)
                    ) : history.push(`/${url}`);
                });
            })
            .catch(() => {
                snackbar.enqueueSnackbar("Não foi possivel salvar o gênero.", {variant: "error"})
            })
            .finally(() => setLoading(false));
    }

    return (
        <DefaultForm
            onSubmit={onSubmit}
            pageTitle={!id ? "Adicionar gênero" : "Editar gênero"}
            Form={Form}
            formProps={FormProps}
            data={genre}
            loading={loading}
        />
    );
};

export default PageForm;