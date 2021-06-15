// @flow
import * as React from 'react';
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import DefaultList from "../../components/PageList";
import genreHttp, {Genre} from "../../util/http/genre-http";
import {useEffect, useState} from "react";
import {BadgeNo, BadgeYes} from "../../components/Badge";

const columnsDefinition: MUIDataTableColumn[] = [
    {
        name: "name",
        label: "Nome"
    },
    {
        name: "is_active",
        label: "Ativo?",
        options: {
            customBodyRender(value) {
                return value ? <BadgeYes /> : <BadgeNo />;
            }
        }
    },
    {
        name: "categories",
        label: "Categorias",
        options: {
            customBodyRender(value) {
                return value ? value.map((category: { name: string; }) => {return category.name}).join(",") : "";
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
    const [data, setData] = useState<Genre[]>([]);
    useEffect(() => {
        genreHttp.list<Genre[]>().then(({data}) => setData(data));
    }, []);
    return (
        <DefaultList
            columnsDefinition={columnsDefinition}
            pageTitle={"Listagem de gêneros"}
            createButtonTitle={"Adicionar gênero"}
            createButtonURL={"/genres/create"}
            tableTitle={""}
            data={data}
        />
    );
};

export default PageList;