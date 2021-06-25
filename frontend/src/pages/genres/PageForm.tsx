// @flow
import * as React from 'react';
import DefaultForm from '../../components/DefaultPageForm';
import Form from "./Form";
import genreHttp from "../../util/http/genre-http";
import {UseFormProps} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import {GenreSchema} from "../../util/vendor/yup";
import {useHistory, useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import {getData, Submit} from "../../util/form";
import {useSnackbar} from "notistack";

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
    const snackbar = useSnackbar();
    const history = useHistory();
    const [genre, setGenre] = useState<{id: string} | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    const [isSubscribed, setIsSubscribed] = useState(true);
    useEffect(() => {
        getData({
            httpProvider,
            setValue: setLoading,
            setData: setGenre,
            id,
            snackbar,
            isSubscribed: () => {return isSubscribed;}
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
            genre,
            snackbar,
            history,
            "Gênero salvo com sucesso.",
            "Não foi possivel salvar o gênero."
        );
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