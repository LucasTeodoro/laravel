// @flow
import * as React from 'react';
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import DefaultList from "../../components/PageList";
import categoryHttp, {Category} from "../../util/http/category-http";
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
            tableTitle={""}
            data={data}
        />
    );
};

export default PageList;