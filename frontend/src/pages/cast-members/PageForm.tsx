// @flow
import * as React from 'react';
import DefaultForm from '../../components/PageForm';
import Form from "./Form";
import castMemberHttp, {CastMember} from "../../util/http/cast-member-http";

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
        />
    );
};

export default PageForm;