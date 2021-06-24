// @flow
import * as React from 'react';
import {Page} from "./Page";
import {Box, Button, ButtonProps, makeStyles, Theme} from "@material-ui/core";
import {FormProvider, useForm} from "react-hook-form";
import {UseFormProps} from "react-hook-form/dist/types";
import {useEffect} from "react";

const useStyles = makeStyles((theme: Theme) => {
    return {
        submit: {
            margin: theme.spacing(1)
        }
    }
});

interface FormPageProps {
    pageTitle: string;
    Form: React.FC<any>;
    formProps?: UseFormProps;
    onSubmit: any;
    data?: any;
    loading?: boolean
}
const PageForm: React.FC<FormPageProps> = ({pageTitle, Form, formProps, onSubmit, data, loading}) => {
    const classes = useStyles();
    const buttonProps: ButtonProps = {
        className: classes.submit,
        color: "secondary",
        variant: "contained",
        disabled: loading
    }
    const methods = useForm(formProps);
    useEffect(() => {
        if(data) {
            methods.reset(data);
        }
    }, [data]);

    return (
        <Page title={pageTitle}>
            <FormProvider {...methods}>
                <form onSubmit={methods.handleSubmit(onSubmit)}>
                <Form
                    loading={loading}
                />
                <Box dir={"rtl"}>
                    <Button
                        {...buttonProps}
                        onClick={() => onSubmit(methods.getValues())}
                    >
                        Salvar
                    </Button>
                    <Button
                        {...buttonProps}
                        type={"submit"}
                    >
                        Salvar e continuar editando
                    </Button>
                </Box>
            </form>
            </FormProvider>
        </Page>
    );
};

export default PageForm;