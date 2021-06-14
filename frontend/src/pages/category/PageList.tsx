// @flow
import * as React from 'react';
import {Chip} from "@material-ui/core";
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import DefaultList from "../../components/PageList";
import categoryHttp, {Category} from "../../util/http/category-http";
import {useEffect, useState} from "react";

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
                return value ? <Chip label={"Sim"} color={"primary"} /> : <Chip label={"Não"} color={"secondary"} />;
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
    const [data, setData] = useState<Category[]>([]);
    useEffect(() => {
        categoryHttp.list<Category[]>().then(({data}) => setData(data));
    }, []);
    return (
        <DefaultList
            columnsDefinition={columnsDefinition}
            pageTitle={"Listagem de categorias"}
            createButtonTitle={"Adicionar categorias"}
            createButtonURL={"/categories/create"}
            tableTitle={"Listagem de categorias"}
            data={data}
        />
    );
};

export default PageList;