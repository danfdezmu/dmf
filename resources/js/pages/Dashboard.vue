<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Briefcase, FileText, FolderKanban, Home } from 'lucide-vue-next';
import { dashboard } from '@/routes';
import { index as experiencesIndex } from '@/routes/admin/experiences';
import { edit as siteEdit } from '@/routes/admin/site';
import { index as projectsIndex } from '@/routes/admin/projects';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold">Panel de administracion</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Gestiona el contenido de tu portafolio sin editar codigo.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <Link
                :href="siteEdit()"
                class="rounded-xl border border-sidebar-border/70 p-5 transition hover:border-sidebar-border hover:bg-muted/30"
            >
                <FileText class="mb-3 size-5 text-primary" />
                <h2 class="font-medium">Contenido del sitio</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Perfil, servicios y pasos de trabajo en la landing.
                </p>
            </Link>

            <Link
                :href="experiencesIndex()"
                class="rounded-xl border border-sidebar-border/70 p-5 transition hover:border-sidebar-border hover:bg-muted/30"
            >
                <Briefcase class="mb-3 size-5 text-primary" />
                <h2 class="font-medium">Experiencia laboral</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Empleos, roles y periodos como en tu LinkedIn.
                </p>
            </Link>

            <Link
                :href="projectsIndex()"
                class="rounded-xl border border-sidebar-border/70 p-5 transition hover:border-sidebar-border hover:bg-muted/30"
            >
                <FolderKanban class="mb-3 size-5 text-primary" />
                <h2 class="font-medium">Proyectos</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Agrega, edita o oculta proyectos y demos.
                </p>
            </Link>

            <a
                href="/"
                target="_blank"
                rel="noopener noreferrer"
                class="rounded-xl border border-sidebar-border/70 p-5 transition hover:border-sidebar-border hover:bg-muted/30"
            >
                <Home class="mb-3 size-5 text-primary" />
                <h2 class="font-medium">Ver sitio publico</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Abre tu pagina principal en una pestana nueva.
                </p>
            </a>
        </div>
    </div>
</template>
