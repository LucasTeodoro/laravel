// @flow
import * as React from 'react';
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import DefaultList from "../../components/DefaultPageList";
import castMemberHttp, {CastMember} from "../../util/http/cast-member-http";
import {useEffect, useState} from "react";
import {getData} from "../../util/form";
import {useSnackbar} from "notistack";

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
    const snackbar = useSnackbar();
    const [isSubscribed, setIsSubscribed] = useState(true);
    useEffect(() => {
        getData({
            httpProvider: castMemberHttp,
            setData,
            snackbar,
            isSubscribed: () => {return isSubscribed;}
        });
        return () => {
            setIsSubscribed(false);
        }
    }, []);
    return (
        <DefaultList
            columnsDefinition={columnsDefinition}
            pageTitle={"Listagem de elenco"}
            createButtonTitle={"Adicionar elenco"}
            createButtonURL={"/cast_members/create"}
            tableTitle={""}
            data={data}
        />
    );
};

export default PageList;