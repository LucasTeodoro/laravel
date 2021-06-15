// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import castMemberHttp, {CastMember} from "../../util/http/cast-member-http";
import {UseFormProps} from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import {CastMemberSchema} from "../../util/vendor/yup";


const FormProps: UseFormProps = {
    resolver: yupResolver(CastMemberSchema),
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