import {RegisterOptions} from "react-hook-form/dist/types/validator";
import {UseFormRegister} from "react-hook-form/dist/types/form";
import HttpResource from "../http/http-resource";
import {ProviderContext} from "notistack";
import {Dispatch, SetStateAction} from "react";
import * as H from "history";


export interface FormProps {
    loading?: boolean
}

export interface RegisterFields {
    name: string,
    options?: RegisterOptions
}

export function register(formRegister: UseFormRegister<any>, fields: RegisterFields[]): object {
    const registries = {};
    fields.forEach((field) => {
        registries[field.name] = formRegister(field.name, field.options);
    });

    return registries;
}

export async function Submit(
    httpProvider: HttpResource,
    formData: any,
    url: string,
    setValue: Dispatch<SetStateAction<boolean>>,
    event: any,
    state: {id: string} | null,
    snackbar: ProviderContext,
    history: H.History<any>,
    successMessage: string,
    errorMessage: string
): Promise<void> {
    setValue(true);
    const http = !state
        ? httpProvider.create(formData)
        : httpProvider.update(state.id, formData);
    try {
        const {data} = await http;
        snackbar.enqueueSnackbar(successMessage, {variant: "success"})
        setTimeout(() => {
            event ? (
                state
                    ? history.replace(`/${url}/${data.id}/edit`)
                    : history.push(`/${url}/${data.id}/edit`)
            ) : history.push(`/${url}`);
        });
    } catch (error) {
        snackbar.enqueueSnackbar(errorMessage, {variant: "error"});
    } finally {
        setValue(false);
    }
}

type GetDataProps = {
    httpProvider: HttpResource,
    setValue?: Dispatch<SetStateAction<boolean>>,
    setData: Dispatch<SetStateAction<any>>,
    id?: string,
    snackbar: ProviderContext,
    isSubscribed: () => {}
}

export async function getData(props: GetDataProps): Promise<void> {
    props.setValue && props.setValue(true);
    try {
        const {data} = props.id
            ? await props.httpProvider.get(props.id)
            : await props.httpProvider.list();
        if (props.isSubscribed()) {
            props.setData(data);
        }
    } catch (e) {
        console.error(e);
        props.snackbar.enqueueSnackbar(
            "Não foi possível carregar as informações.",
            {variant: "error"}
        );
    } finally {
        props.setValue && props.setValue(false);
    }
}