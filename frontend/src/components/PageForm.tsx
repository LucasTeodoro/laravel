// @flow
import * as React from 'react';
import {Page} from "./Page";
import {Box, Button, ButtonProps, makeStyles, Theme} from "@material-ui/core";
import {useForm} from "react-hook-form";
import {UseFormProps} from "react-hook-form/dist/types";

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
    formProps?: UseFormProps
    onSubmit: any;
}
const PageForm: React.FC<FormPageProps> = ({pageTitle, Form, formProps, onSubmit}) => {
    const classes = useStyles();
    const buttonProps: ButtonProps = {
        className: classes.submit,
        color: "secondary",
        variant: "contained"
    }
    const {handleSubmit, getValues, setValue, register, watch, formState: {errors}} = useForm(formProps);
    return (
        <Page title={pageTitle}>
            <form onSubmit={handleSubmit(onSubmit)}>
                <Form
                    setValue={setValue}
                    register={register}
                    watch={watch}
                    errors={errors}
                />
                <Box dir={"rtl"}>
                    <Button
                        {...buttonProps}
                        onClick={() => onSubmit(getValues())}
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
        </Page>
    );
};

export default PageForm;