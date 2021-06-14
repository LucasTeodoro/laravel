import {RouteProps} from "react-router-dom";
import Dashboard from "../pages/Dashboard";
import CategoryList from "../pages/category/PageList";
import CastMemberList from "../pages/cast-members/PageList";
import GenreList from "../pages/genres/PageList";
import CategoryForm from "../pages/category/PageForm";
import GenreForm from "../pages/genres/PageForm";
import CastMemberForm from "../pages/cast-members/PageForm";

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
        component: CategoryForm,
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
        component: CastMemberForm,
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
        label: "Criar gênero",
        path: "/genres/create",
        component: GenreForm,
        exact: true
    },
];

export default routes;