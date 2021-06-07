// @flow
import * as React from 'react';
import {AppBar, Toolbar, makeStyles, Typography, Button, Theme} from "@material-ui/core";
import logo from '../../static/img/logo.png';
import {Menu} from './Menu';

const useStyles = makeStyles((theme: Theme) => ({
    toolbar: {
        backgroundColor: '#ffffff'
    },
    title: {
        flexGrow: 1,
        textAlign: "center",
    },
    logo: {
        width: 120,
        [theme.breakpoints.up("sm")]: {
            width: 218
        },
    }
}))

export const Navbar: React.FC = () => {
    const classes = useStyles();

    return (
        <AppBar>
            <Toolbar className={classes.toolbar}>
                <Menu /> 
                <Typography className={classes.title}>
                    <img src={logo} alt={'logo'} className={classes.logo}/>
                </Typography>
                <Button>Login</Button>
            </Toolbar>
        </AppBar>
    );
};