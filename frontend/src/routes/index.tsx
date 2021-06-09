import {RouteProps} from "react-router-dom";
import Dashboard from "../pages/Dashboard";
import CategoryList from "../pages/category/List";
import CastMemberList from "../pages/members/List";
import GenreList from "../pages/genres/List";

export interface MyRouteProps extends RouteProps {
    name: string;
    label: string;
}

const routes: MyRouteProps[] = [
    {
        name: "dashboard",
        label: "Dashboard",
        path: "/",
        component: Dashboard,
        exact: true
    },
    {
        name: "categories.list",
        label: "Listar categorias",
        path: "/categories",
        component: CategoryList,
        exact: true
    },
    {
        name: "categories.create",
        label: "Criar categoria",
        path: "/categories/create",
        component: CategoryList,
        exact: true
    },
    {
        name: "cast_members.list",
        label: "Listar elenco",
        path: "/cast_members",
        component: CastMemberList,
        exact: true
    },
    {
        name: "cast_members.create",
        label: "Criar elenco",
        path: "/cast_members/create",
        component: CastMemberList,
        exact: true
    },
    {
        name: "genres.list",
        label: "Listar gênero",
        path: "/genres",
        component: GenreList,
        exact: true
    },
    {
        name: "genres.create",
        label: "Criar elenco",
        path: "/genres/create",
        component: GenreList,
        exact: true
    },
];

export default routes;