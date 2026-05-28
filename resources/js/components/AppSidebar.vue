<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Briefcase, FileText, FolderKanban, LayoutGrid } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { edit as siteEdit } from '@/routes/admin/site';
import { index as experiencesIndex } from '@/routes/admin/experiences';
import { index as projectsIndex } from '@/routes/admin/projects';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Contenido del sitio',
        href: siteEdit(),
        icon: FileText,
    },
    {
        title: 'Experiencia laboral',
        href: experiencesIndex(),
        icon: Briefcase,
    },
    {
        title: 'Proyectos',
        href: projectsIndex(),
        icon: FolderKanban,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Ver sitio publico',
        href: '/',
        icon: LayoutGrid,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
