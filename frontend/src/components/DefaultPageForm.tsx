// @flow
import * as React from 'react';
import {Page} from "./Page";
import {FormProvider} from "react-hook-form";
import {UseFormReturn} from "react-hook-form/dist/types";
import {DefaultForm} from "./DefaultForm";

interface FormPageProps {
    pageTitle: string;
    onSubmit: any;
    data?: any;
    loading?: boolean;
    UseFormMethods: UseFormReturn<any>;
}
const DefaultPageForm: React.FC<FormPageProps> = ({pageTitle, UseFormMethods, onSubmit, data, ...others}) => {

    return (
        <Page title={pageTitle}>
            <FormProvider {...UseFormMethods}>
                <DefaultForm onSubmit={onSubmit}>
                    {others.children}
                </DefaultForm>
            </FormProvider>
        </Page>
    );
};

export default DefaultPageForm;