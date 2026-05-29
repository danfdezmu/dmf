<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import ExperienceController from '@/actions/App/Http/Controllers/Admin/ExperienceController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/experiences';

type Experience = {
    id: number;
    company: string;
    role: string;
    location: string | null;
    period: string;
    sort_order: number;
    is_published: boolean;
    logo: string | null;
    company_initials: string;
};

defineProps<{
    experiences: Experience[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Experiencia laboral', href: index() },
        ],
    },
});
</script>

<template>
    <Head title="Experiencia laboral" />

    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <Heading
                variant="small"
                title="Experiencia laboral"
                description="Agrega los empleos y roles que aparecen en tu LinkedIn o CV."
            />
            <Button as-child>
                <Link :href="create()">Nueva experiencia</Link>
            </Button>
        </div>

        <div v-if="experiences.length" class="overflow-hidden rounded-xl border border-sidebar-border/70">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-sidebar-border/70 bg-muted/40">
                    <tr>
                        <th class="px-4 py-3 font-medium">Logo</th>
                        <th class="px-4 py-3 font-medium">Empresa</th>
                        <th class="px-4 py-3 font-medium">Rol</th>
                        <th class="px-4 py-3 font-medium">Periodo</th>
                        <th class="px-4 py-3 font-medium">Publicado</th>
                        <th class="px-4 py-3 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="experience in experiences"
                        :key="experience.id"
                        class="border-b border-sidebar-border/50 last:border-0"
                    >
                        <td class="px-4 py-3">
                            <div
                                class="flex size-10 items-center justify-center overflow-hidden rounded-lg border border-sidebar-border/70 bg-white p-1"
                            >
                                <img
                                    v-if="experience.logo"
                                    :src="experience.logo"
                                    :alt="experience.company"
                                    class="max-h-full max-w-full object-contain"
                                />
                                <span
                                    v-else
                                    class="text-xs font-bold text-muted-foreground"
                                >
                                    {{ experience.company_initials }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ experience.company }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ experience.role }}</td>
                        <td class="px-4 py-3">{{ experience.period }}</td>
                        <td class="px-4 py-3">
                            {{ experience.is_published ? 'Si' : 'No' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Button as-child variant="outline" size="sm">
                                    <Link :href="ExperienceController.edit.url(experience.id)">
                                        Editar
                                    </Link>
                                </Button>
                                <Form
                                    v-bind="ExperienceController.destroy.form(experience.id)"
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
            Aun no hay experiencia registrada. Copia tus empleos desde LinkedIn y agregalos aqui.
        </p>
    </div>
</template>
