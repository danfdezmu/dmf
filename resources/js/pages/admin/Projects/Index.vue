<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import ProjectController from '@/actions/App/Http/Controllers/Admin/ProjectController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/projects';

type Project = {
    id: number;
    title: string;
    description: string;
    stack: string;
    url: string | null;
    sort_order: number;
    is_published: boolean;
};

defineProps<{
    projects: Project[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Proyectos', href: index() },
        ],
    },
});
</script>

<template>
    <Head title="Proyectos" />

    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <Heading
                variant="small"
                title="Proyectos"
                description="Administra los proyectos visibles en tu portafolio."
            />
            <Button as-child>
                <Link :href="create()">Nuevo proyecto</Link>
            </Button>
        </div>

        <div v-if="projects.length" class="overflow-hidden rounded-xl border border-sidebar-border/70">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-sidebar-border/70 bg-muted/40">
                    <tr>
                        <th class="px-4 py-3 font-medium">Titulo</th>
                        <th class="px-4 py-3 font-medium">Stack</th>
                        <th class="px-4 py-3 font-medium">Orden</th>
                        <th class="px-4 py-3 font-medium">Publicado</th>
                        <th class="px-4 py-3 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="project in projects"
                        :key="project.id"
                        class="border-b border-sidebar-border/50 last:border-0"
                    >
                        <td class="px-4 py-3">{{ project.title }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ project.stack }}</td>
                        <td class="px-4 py-3">{{ project.sort_order }}</td>
                        <td class="px-4 py-3">
                            {{ project.is_published ? 'Si' : 'No' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Button as-child variant="outline" size="sm">
                                    <Link :href="ProjectController.edit.url(project.id)">
                                        Editar
                                    </Link>
                                </Button>
                                <Form
                                    v-bind="ProjectController.destroy.form(project.id)"
                                    class="inline"
                                >
                                    <Button
                                        type="submit"
                                        variant="ghost"
                                        size="sm"
                                        class="text-destructive"
                                    >
                                        Eliminar
                                    </Button>
                                </Form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p v-else class="text-sm text-muted-foreground">
            Aun no hay proyectos. Crea el primero para mostrarlo en tu landing.
        </p>
    </div>
</template>
