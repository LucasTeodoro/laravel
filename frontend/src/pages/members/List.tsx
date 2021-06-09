// @flow
import * as React from 'react';
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import DefaultList from "../../components/List";

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
                return value === 1 ? <span>Diretor</span> : <span>Ator</span>;
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
            pageTitle={"Listagem de elenco"}
            createButtonTitle={"Adicionar elenco"}
            createButtonURL={"/cast_members/create"}
            tableTitle={"Listagem de elenco"}
            pageURL={"cast_members"}
        />
    );
};

export default List;