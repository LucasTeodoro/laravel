// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import genreHttp, {Genre} from "../../util/http/genre-http";
import {UseFormProps} from "react-hook-form";

const FormProps: UseFormProps = {
    defaultValues: {
        categories_id: [],
        is_active: true
    }
}

const PageForm = () => {
    function onSubmit(formData: any, event: any) {
        console.log(event);
        genreHttp
            .create(formData)
            .then((response:{data: Genre}) => console.log(response));
    }

    return (
        <DefaultForm
            onSubmit={onSubmit}
            pageTitle={"Adicionar genêros"}
            Form={Form}
            formProps={FormProps}
        />
    );
};

export default PageForm;