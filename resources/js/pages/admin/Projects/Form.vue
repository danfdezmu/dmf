<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import ProjectController from '@/actions/App/Http/Controllers/Admin/ProjectController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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

const props = defineProps<{
    project: Project | null;
}>();

const isEditing = props.project !== null;

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Proyectos', href: index() },
            { title: 'Formulario', href: create() },
        ],
    },
});
</script>

<template>
    <Head :title="isEditing ? 'Editar proyecto' : 'Nuevo proyecto'" />

    <div class="flex max-w-2xl flex-col gap-6">
        <Heading
            variant="small"
            :title="isEditing ? 'Editar proyecto' : 'Nuevo proyecto'"
            description="Los proyectos publicados aparecen en tu pagina principal."
        />

        <Form
            v-bind="
                isEditing
                    ? ProjectController.update.form(project!.id)
                    : ProjectController.store.form()
            "
            class="space-y-6"
            #default="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="title">Titulo</Label>
                <Input
                    id="title"
                    name="title"
                    :default-value="project?.title"
                    required
                />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Descripcion</Label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    required
                    :default-value="project?.description"
                    class="flex min-h-[100px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                />
                <InputError :message="errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="stack">Tecnologias / stack</Label>
                <Input
                    id="stack"
                    name="stack"
                    :default-value="project?.stack"
                    required
                />
                <InputError :message="errors.stack" />
            </div>

            <div class="grid gap-2">
                <Label for="url">URL del proyecto (opcional)</Label>
                <Input
                    id="url"
                    name="url"
                    type="url"
                    :default-value="project?.url ?? ''"
                />
                <InputError :message="errors.url" />
            </div>

            <div class="grid gap-2">
                <Label for="sort_order">Orden</Label>
                <Input
                    id="sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    :default-value="project?.sort_order ?? 0"
                    required
                />
                <InputError :message="errors.sort_order" />
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="is_published"
                    name="is_published"
                    type="checkbox"
                    value="1"
                    :checked="project?.is_published ?? true"
                    class="size-4 rounded border border-input"
                />
                <Label for="is_published">Publicar en la landing</Label>
                <InputError :message="errors.is_published" />
            </div>

            <div class="flex gap-3">
                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Guardando...' : isEditing ? 'Actualizar' : 'Crear proyecto' }}
                </Button>
                <Button as-child variant="outline">
                    <a :href="index()">Cancelar</a>
                </Button>
            </div>
        </Form>
    </div>
</template>
