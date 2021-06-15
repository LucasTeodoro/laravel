// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import {UseFormProps} from "react-hook-form";
import categoryHttp, {Category} from "../../util/http/category-http";
import {yupResolver} from "@hookform/resolvers/yup";
import {CategorySchema} from "../../util/vendor/yup";

const FormProps: UseFormProps = {
    resolver: yupResolver(CategorySchema),
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