// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import {UseFormProps} from "react-hook-form";
import categoryHttp, {Category} from "../../util/http/category-http";
import * as yup from "yup";
import {yupResolver} from "@hookform/resolvers/yup";

const schema = yup.object().shape({
    name: yup.string().required(),
    description: yup.string(),
    is_active: yup.boolean()
});

const FormProps: UseFormProps = {
    resolver: yupResolver(schema),
    defaultValues: {
        is_active: true
    }
}

const PageForm = () => {
    function onSubmit(formData: any, event: any) {
        console.log(event);
        categoryHttp
            .create(formData)
            .then((response:{data: Category}) => console.log(response));
    }

    return (
        <DefaultForm
            onSubmit={onSubmit}
            pageTitle={"Adicionar categoria"}
            Form={Form}
            formProps={FormProps}
        />
    );
};

export default PageForm;