import React from 'react';
import Typography from '@material-ui/core/Typography';
import {Link, Breadcrumbs as MuiBreadcrumbs, LinkProps, makeStyles, Theme, createStyles} from '@material-ui/core';
import { Route } from 'react-router';
import { Link as RouterLink } from 'react-router-dom';
import {Location} from 'history';

const breadcrumbNameMap: { [key: string]: string } = {
    '/inbox': 'Inbox',
    '/inbox/important': 'Important',
    '/trash': 'Trash',
    '/spam': 'Spam',
    '/drafts': 'Drafts',
};

const useStyles = makeStyles((theme: Theme) =>
    createStyles({
        root: {
            display: 'flex',
            flexDirection: 'column',
            width: 360,
        },
        lists: {
            backgroundColor: theme.palette.background.paper,
            marginTop: theme.spacing(1),
        },
        nested: {
            paddingLeft: theme.spacing(4),
        },
    }),
);

interface LinkRouterProps extends LinkProps {
    to: string;
    replace?: boolean;
}

const LinkRouter = (props: LinkRouterProps) => <Link {...props} component={RouterLink as any} />;

export default function Breadcrumbs() {
    const classes = useStyles();
    const makeBreadcrumbs = (location: Location) => {
        const pathnames = location.pathname.split('/').filter((x) => x);
        pathnames.unshift("/");

        return (
            <MuiBreadcrumbs aria-label="breadcrumb">
                {
                    pathnames.map((value, index) => {
                        const last = index === pathnames.length - 1;
                        const to = `/${pathnames.slice(0, index + 1).join('/')}`;

                        return last ? (
                            <Typography color="textPrimary" key={to}>
                                {breadcrumbNameMap[to]}
                            </Typography>
                        ) : (
                            <LinkRouter color="inherit" to={to} key={to}>
                                {breadcrumbNameMap[to]}
                            </LinkRouter>
                        );
                    })
                }
            </MuiBreadcrumbs>
        );
    }
    return (
        <div className={classes.root}>
            <Route>
                {
                    ({location}: {location: Location}) => makeBreadcrumbs(location)
                }
            </Route>
        </div>
    );
}