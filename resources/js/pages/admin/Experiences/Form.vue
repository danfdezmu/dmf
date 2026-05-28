<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import ExperienceController from '@/actions/App/Http/Controllers/Admin/ExperienceController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/experiences';

type Experience = {
    id: number;
    company: string;
    role: string;
    location: string | null;
    started_at: string;
    ended_at: string | null;
    description: string | null;
    sort_order: number;
    is_published: boolean;
};

const props = defineProps<{
    experience: Experience | null;
}>();

const isEditing = props.experience !== null;

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Experiencia laboral', href: index() },
            { title: 'Formulario', href: create() },
        ],
    },
});
</script>

<template>
    <Head :title="isEditing ? 'Editar experiencia' : 'Nueva experiencia'" />

    <div class="flex max-w-2xl flex-col gap-6">
        <Heading
            variant="small"
            :title="isEditing ? 'Editar experiencia' : 'Nueva experiencia'"
            description="Deja la fecha de fin vacia si sigues trabajando ahi (se mostrara como Presente)."
        />

        <Form
            v-bind="
                isEditing
                    ? ExperienceController.update.form(experience!.id)
                    : ExperienceController.store.form()
            "
            class="space-y-6"
            #default="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="company">Empresa</Label>
                <Input
                    id="company"
                    name="company"
                    :default-value="experience?.company"
                    required
                />
                <InputError :message="errors.company" />
            </div>

            <div class="grid gap-2">
                <Label for="role">Rol / puesto</Label>
                <Input
                    id="role"
                    name="role"
                    :default-value="experience?.role"
                    required
                />
                <InputError :message="errors.role" />
            </div>

            <div class="grid gap-2">
                <Label for="location">Ubicacion (opcional)</Label>
                <Input
                    id="location"
                    name="location"
                    :default-value="experience?.location ?? ''"
                />
                <InputError :message="errors.location" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="started_at">Fecha de inicio</Label>
                    <Input
                        id="started_at"
                        name="started_at"
                        type="date"
                        :default-value="experience?.started_at"
                        required
                    />
                    <InputError :message="errors.started_at" />
                </div>
                <div class="grid gap-2">
                    <Label for="ended_at">Fecha de fin (opcional)</Label>
                    <Input
                        id="ended_at"
                        name="ended_at"
                        type="date"
                        :default-value="experience?.ended_at ?? ''"
                    />
                    <InputError :message="errors.ended_at" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="description">Descripcion (opcional)</Label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    :default-value="experience?.description ?? ''"
                    class="flex min-h-[100px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                />
                <InputError :message="errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="sort_order">Orden</Label>
                <Input
                    id="sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    :default-value="experience?.sort_order ?? 0"
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
                    :checked="experience?.is_published ?? true"
                    class="size-4 rounded border border-input"
                />
                <Label for="is_published">Publicar en la landing</Label>
                <InputError :message="errors.is_published" />
            </div>

            <div class="flex gap-3">
                <Button type="submit" :disabled="processing">
                    {{
                        processing
                            ? 'Guardando...'
                            : isEditing
                              ? 'Actualizar'
                              : 'Crear experiencia'
                    }}
                </Button>
                <Button as-child variant="outline">
                    <a :href="index()">Cancelar</a>
                </Button>
            </div>
        </Form>
    </div>
</template>
