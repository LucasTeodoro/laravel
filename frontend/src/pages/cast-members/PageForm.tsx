// @flow
import * as React from 'react';
import DefaultForm from '../../components/DefaultPageForm';
import Form from "./Form";
import castMemberHttp from "../../util/http/cast-member-http";
import {UseFormProps} from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import {CastMemberSchema} from "../../util/vendor/yup";
import {useHistory, useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import {getData, Submit} from "../../util/form";
import {useSnackbar} from "notistack";
import {SubmitActions} from "../../components/SubmitActions";


const FormProps: UseFormProps = {
    resolver: yupResolver(CastMemberSchema),
}

const url = 'cast_members';
const httpProvider = castMemberHttp;

const PageForm = () => {
    const { id } = useParams<{ id: string }>();
    const snackbar = useSnackbar();
    const history = useHistory();
    const [castMember, setCastMember] = useState<{id: string} | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    const [isSubscribed, setIsSubscribed] = useState(true);
    useEffect(() => {
        if(!id) return;
        getData({
            httpProvider,
            setValue: setLoading,
            setData: setCastMember,
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
            castMember,
            snackbar,
            history,
            "Elenco salvo com sucesso.",
            "Não foi possivel salvar o elenco."
        );
    }

    return (
        <DefaultForm
            onSubmit={onSubmit}
            pageTitle={!id ? "Adicionar elenco" : "Editar elenco"}
            data={castMember}
            loading={loading}
            UseFormMethods={}
        >
            <Form />
            <SubmitActions handleSave={() => {}} />
        </DefaultForm>
    );
};

export default PageForm;