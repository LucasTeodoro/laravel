// @flow
import * as React from 'react';
import {Chip, createMuiTheme, MuiThemeProvider} from "@material-ui/core";
import theme from "../theme";

const defaultTheme = createMuiTheme({
    palette: {
        primary: theme.palette.success,
        secondary: theme.palette.error
    }
});

export const BadgeYes = () => {
    return (
        <MuiThemeProvider theme={defaultTheme}>
            <Chip label={"Sim"} color={"primary"} />
        </MuiThemeProvider>
    );
};

export const BadgeNo = () => {
    return (
        <MuiThemeProvider theme={defaultTheme}>
            <Chip label={"Não"} color={"secondary"} />
        </MuiThemeProvider>
    );
};