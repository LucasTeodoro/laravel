// @flow
import * as React from 'react';
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import DefaultList from "../../components/PageList";
import castMemberHttp, {CastMember} from "../../util/http/cast-member-http";
import {useEffect, useState} from "react";

export const CastMemberTypeMap: any = {
    1: "Diretor",
    2: "Ator"
}

const columnsDefinition: MUIDataTableColumn[] = [
    {
        name: "name",
        label: "Nome"
    },
    {
        name: "type",
        label: "Tipo",
        options: {
            customBodyRender(value) {
                return CastMemberTypeMap[value];
            }
        }
    },
    {
        name: "created_at",
        label: "Criado em",
        options: {
            customBodyRender(value) {
                return <span>{format(parseISO(value), "dd/MM/yyyy")}</span>;
            }
        }
    }
]

const PageList = () => {
    const [data, setData] = useState<CastMember[]>([]);
    useEffect(() => {
        castMemberHttp.list<CastMember[]>().then(({data}) => setData(data));
    }, []);
    return (
        <DefaultList
            columnsDefinition={columnsDefinition}
            pageTitle={"Listagem de elenco"}
            createButtonTitle={"Adicionar elenco"}
            createButtonURL={"/cast_members/create"}
            tableTitle={"Listagem de elenco"}
            data={data}
        />
    );
};

export default PageList;