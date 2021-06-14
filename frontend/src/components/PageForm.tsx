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
    form: React.FC<any>;
    formProps?: UseFormProps
    onSubmit: any;
};
const PageForm: React.FC<FormPageProps> = (props) => {
    const classes = useStyles();
    const buttonProps: ButtonProps = {
        className: classes.submit,
        variant: "outlined"
    }
    const {handleSubmit, getValues, setValue, register, watch} = useForm(props.formProps);
    return (
        <Page title={props.pageTitle}>
            <form onSubmit={handleSubmit(props.onSubmit)}>
                {props.form({setValue, register, watch})}
                <Box dir={"rtl"}>
                    <Button {...buttonProps} onClick={() => props.onSubmit(getValues())}>Salvar</Button>
                    <Button {...buttonProps} type={"submit"}>Salvar e continuar editando</Button>
                </Box>
            </form>
        </Page>
    );
};

export default PageForm;