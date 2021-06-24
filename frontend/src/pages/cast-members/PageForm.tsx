// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import castMemberHttp from "../../util/http/cast-member-http";
import {UseFormProps} from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import {CastMemberSchema} from "../../util/vendor/yup";
import {useHistory, useParams} from "react-router-dom";
import {useSnackbar} from "notistack";
import {useEffect, useState} from "react";


const FormProps: UseFormProps = {
    resolver: yupResolver(CastMemberSchema),
}

const url = 'cast_members';
const httpProvider = castMemberHttp;

const PageForm = () => {
    const { id } = useParams<{ id: string }>();
    const history = useHistory();
    const snackbar = useSnackbar();
    const [castMember, setcastMember] = useState<{id: string} | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    useEffect(() => {
        if(!id) return;
        setLoading(true);
        httpProvider.get(id)
            .then(({data}) => {
                setcastMember(data);
            }).finally(() => setLoading(false));
    }, [id]);
    function onSubmit(formData: any, event: any) {
        setLoading(true);
        const http = !castMember
            ? httpProvider.create(formData)
            : httpProvider.update(castMember.id, formData);

        http
            .then(({data}) => {
                snackbar.enqueueSnackbar("Elenco salvo com sucesso.", {variant: "success"})
                setTimeout(() => {
                    event ? (
                        castMember
                            ? history.replace(`/${url}/${data.id}/edit`)
                            : history.push(`/${url}/${data.id}/edit`)
                    ) : history.push(`/${url}`);
                });
            })
            .catch(() => {
                snackbar.enqueueSnackbar("Não foi possivel salvar o elenco.", {variant: "error"})
            })
            .finally(() => setLoading(false));
    }

    return (
        <DefaultForm
            onSubmit={onSubmit}
            pageTitle={!id ? "Adicionar elenco" : "Editar elenco"}
            Form={Form}
            formProps={FormProps}
            data={castMember}
            loading={loading}
        />
    );
};

export default PageForm;