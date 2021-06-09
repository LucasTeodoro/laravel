// @flow
import * as React from 'react';
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import DefaultList from "../../components/List";
import {Chip} from "@material-ui/core";

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

const List = () => {
    return (
        <DefaultList
            columnsDefinition={columnsDefinition}
            pageTitle={"Listagem de gêneros"}
            createButtonTitle={"Adicionar gênero"}
            createButtonURL={"/genres/create"}
            tableTitle={"Listagem de gênero"}
            pageURL={"genres"}
        />
    );
};

export default List;