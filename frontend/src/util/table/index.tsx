import * as React from 'react';
import MUIDataTable, {MUIDataTableColumn} from "mui-datatables";
import {useEffect, useState} from "react";
import {httpVideo} from "../http";

type Props = {
    url: string,
    columnsDefinitions: MUIDataTableColumn[],
    title?: string
};
const Index = (props: Props) => {
    const [data, setData] = useState([]);
    useEffect(() => {
        httpVideo.get(props.url).then(
            response => setData(response.data)
        )
    }, [])
    return (
        <MUIDataTable
            columns={props.columnsDefinitions}
            title={props.title}
            data={data}
        />
    );
};

export default Index;