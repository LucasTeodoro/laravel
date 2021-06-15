// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import castMemberHttp, {CastMember} from "../../util/http/cast-member-http";
import {UseFormProps} from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from 'yup';

const schema = yup.object().shape({
    name: yup.string().required(),
    type: yup.number().required()
});
const FormProps: UseFormProps = {
    resolver: yupResolver(schema),
}

const PageForm = () => {
    function onSubmit(formData: any, event: any) {
        console.log(event);
        castMemberHttp
            .create(formData)
            .then((response:{data: CastMember}) => console.log(response));
    }

    return (
        <DefaultForm
            onSubmit={onSubmit}
            pageTitle={"Adicionar elenco"}
            Form={Form}
            formProps={FormProps}
        />
    );
};

export default PageForm;