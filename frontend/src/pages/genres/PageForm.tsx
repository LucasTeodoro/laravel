// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import genreHttp, {Genre} from "../../util/http/genre-http";

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
        />
    );
};

export default PageForm;