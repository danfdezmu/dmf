<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ExperienceController from '@/actions/App/Http/Controllers/Admin/ExperienceController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { create, index } from '@/routes/admin/experiences';

type LinkPreview = {
    title: string;
    description: string | null;
    image: string | null;
    url: string;
    domain: string;
};

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
    logo: string | null;
    logo_url: string | null;
    website_url: string | null;
    link_preview: LinkPreview | null;
    company_initials: string;
};

const props = defineProps<{
    experience: Experience | null;
}>();

const isEditing = props.experience !== null;
const logoPreview = ref<string | null>(props.experience?.logo ?? null);

const form = useForm({
    company: props.experience?.company ?? '',
    role: props.experience?.role ?? '',
    location: props.experience?.location ?? '',
    started_at: props.experience?.started_at ?? '',
    ended_at: props.experience?.ended_at ?? '',
    description: props.experience?.description ?? '',
    sort_order: props.experience?.sort_order ?? 0,
    is_published: props.experience?.is_published ?? true,
    logo_url: props.experience?.logo_url ?? '',
    logo: null as File | null,
    remove_logo: false,
    website_url: props.experience?.website_url ?? '',
    refresh_preview: false,
});

function onLogoChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];

    if (!file) {
        return;
    }

    form.logo = file;
    form.remove_logo = false;
    logoPreview.value = URL.createObjectURL(file);
}

function clearLogo(): void {
    form.logo = null;
    form.logo_url = '';
    form.remove_logo = true;
    logoPreview.value = null;
}

function submit(): void {
    const options = {
        forceFormData: true,
        preserveScroll: true,
    };

    if (isEditing) {
        form.patch(ExperienceController.update.url(props.experience!.id), options);

        return;
    }

    form.post(ExperienceController.store.url(), options);
}

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
            description="Logo, sitio web con vista previa y datos del puesto. Deja la fecha de fin vacia si sigues trabajando ahi."
        />

        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-3">
                <Label>Logo de la empresa</Label>
                <div class="flex items-center gap-4">
                    <div
                        class="flex size-16 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-input bg-white p-2"
                    >
                        <img
                            v-if="logoPreview"
                            :src="logoPreview"
                            alt="Vista previa del logo"
                            class="max-h-full max-w-full object-contain"
                        />
                        <span
                            v-else
                            class="text-sm font-bold text-muted-foreground"
                        >
                            {{ form.company.slice(0, 2).toUpperCase() || '?' }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Input
                            type="file"
                            accept="image/png,image/jpeg,image/svg+xml,image/webp"
                            @change="onLogoChange"
                        />
                        <Button
                            v-if="logoPreview"
                            type="button"
                            variant="outline"
                            size="sm"
                            @click="clearLogo"
                        >
                            Quitar logo
                        </Button>
                    </div>
                </div>
                <div class="grid gap-2">
                    <Label for="logo_url">O URL del logo (opcional)</Label>
                    <Input
                        id="logo_url"
                        v-model="form.logo_url"
                        type="text"
                        placeholder="https://... o /images/logos/empresa.svg"
                        @input="form.remove_logo = false"
                    />
                    <InputError :message="form.errors.logo" />
                    <InputError :message="form.errors.logo_url" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="website_url">Sitio web de la empresa</Label>
                <Input
                    id="website_url"
                    v-model="form.website_url"
                    type="url"
                    placeholder="https://www.empresa.com"
                />
                <InputError :message="form.errors.website_url" />
                <p class="text-xs text-muted-foreground">
                    Al guardar se genera una vista previa automatica (titulo, descripcion e imagen).
                </p>
            </div>

            <div
                v-if="experience?.link_preview"
                class="overflow-hidden rounded-xl border border-sidebar-border/70"
            >
                <div
                    v-if="experience.link_preview.image"
                    class="aspect-[1.91/1] overflow-hidden bg-muted"
                >
                    <img
                        :src="experience.link_preview.image"
                        :alt="experience.link_preview.title"
                        class="size-full object-cover"
                    />
                </div>
                <div class="p-4">
                    <p class="text-xs text-blue-600">{{ experience.link_preview.domain }}</p>
                    <p class="font-medium">{{ experience.link_preview.title }}</p>
                    <p
                        v-if="experience.link_preview.description"
                        class="mt-1 line-clamp-2 text-sm text-muted-foreground"
                    >
                        {{ experience.link_preview.description }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="refresh_preview"
                    v-model="form.refresh_preview"
                    type="checkbox"
                    class="size-4 rounded border border-input"
                />
                <Label for="refresh_preview">Actualizar vista previa del sitio al guardar</Label>
            </div>

            <div class="grid gap-2">
                <Label for="company">Empresa</Label>
                <Input id="company" v-model="form.company" required />
                <InputError :message="form.errors.company" />
            </div>

            <div class="grid gap-2">
                <Label for="role">Rol / puesto</Label>
                <Input id="role" v-model="form.role" required />
                <InputError :message="form.errors.role" />
            </div>

            <div class="grid gap-2">
                <Label for="location">Ubicacion (opcional)</Label>
                <Input id="location" v-model="form.location" />
                <InputError :message="form.errors.location" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="started_at">Fecha de inicio</Label>
                    <Input id="started_at" v-model="form.started_at" type="date" required />
                    <InputError :message="form.errors.started_at" />
                </div>
                <div class="grid gap-2">
                    <Label for="ended_at">Fecha de fin (opcional)</Label>
                    <Input id="ended_at" v-model="form.ended_at" type="date" />
                    <InputError :message="form.errors.ended_at" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="description">Descripcion (opcional)</Label>
                <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    class="flex min-h-[100px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                />
                <InputError :message="form.errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="sort_order">Orden</Label>
                <Input
                    id="sort_order"
                    v-model="form.sort_order"
                    type="number"
                    min="0"
                    required
                />
                <InputError :message="form.errors.sort_order" />
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="is_published"
                    v-model="form.is_published"
                    type="checkbox"
                    class="size-4 rounded border border-input"
                />
                <Label for="is_published">Publicar en la landing</Label>
                <InputError :message="form.errors.is_published" />
            </div>

            <div class="flex gap-3">
                <Button type="submit" :disabled="form.processing">
                    {{
                        form.processing
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
        </form>
    </div>
</template>
