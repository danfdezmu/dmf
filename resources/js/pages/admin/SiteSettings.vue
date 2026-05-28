<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import SiteSettingController from '@/actions/App/Http/Controllers/Admin/SiteSettingController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import { edit } from '@/routes/admin/site';

type Service = {
    title: string;
    description: string;
};

type Education = {
    institution: string;
    degree: string;
    field: string;
};

type Settings = {
    brand_label: string;
    name: string;
    role: string;
    intro: string;
    linkedin_url: string;
    contact_email: string;
    work_steps_text: string;
    services: Service[];
    education: Education[];
};

const props = defineProps<{
    settings: Settings;
}>();

const form = useForm({
    brand_label: props.settings.brand_label,
    name: props.settings.name,
    role: props.settings.role,
    intro: props.settings.intro,
    linkedin_url: props.settings.linkedin_url,
    contact_email: props.settings.contact_email,
    work_steps_text: props.settings.work_steps_text,
    services: [...props.settings.services],
    education: [...(props.settings.education?.length ? props.settings.education : [{ institution: '', degree: '', field: '' }])],
});

function addEducation(): void {
    form.education.push({ institution: '', degree: '', field: '' });
}

function removeEducation(index: number): void {
    if (form.education.length > 1) {
        form.education.splice(index, 1);
    }
}

function addService(): void {
    form.services.push({ title: '', description: '' });
}

function removeService(index: number): void {
    if (form.services.length > 1) {
        form.services.splice(index, 1);
    }
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard() },
            { title: 'Contenido del sitio', href: edit() },
        ],
    },
});
</script>

<template>
    <Head title="Contenido del sitio" />

    <div class="flex max-w-3xl flex-col gap-6">
        <Heading
            variant="small"
            title="Contenido del sitio"
            description="Edita la informacion que se muestra en tu pagina publica."
        />

        <form class="space-y-6" @submit.prevent="form.patch(SiteSettingController.update.url())">
            <div class="grid gap-2">
                <Label for="brand_label">Marca / etiqueta</Label>
                <Input id="brand_label" v-model="form.brand_label" required />
                <InputError :message="form.errors.brand_label" />
            </div>

            <div class="grid gap-2">
                <Label for="name">Nombre</Label>
                <Input id="name" v-model="form.name" required />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="role">Rol / titulo</Label>
                <Input id="role" v-model="form.role" required />
                <InputError :message="form.errors.role" />
            </div>

            <div class="grid gap-2">
                <Label for="intro">Introduccion</Label>
                <textarea
                    id="intro"
                    v-model="form.intro"
                    rows="4"
                    required
                    class="flex min-h-[100px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                />
                <InputError :message="form.errors.intro" />
            </div>

            <div class="grid gap-2">
                <Label for="linkedin_url">LinkedIn</Label>
                <Input id="linkedin_url" v-model="form.linkedin_url" type="url" required />
                <InputError :message="form.errors.linkedin_url" />
            </div>

            <div class="grid gap-2">
                <Label for="contact_email">Correo de contacto</Label>
                <Input id="contact_email" v-model="form.contact_email" type="email" required />
                <InputError :message="form.errors.contact_email" />
            </div>

            <div class="grid gap-2">
                <Label for="work_steps_text">Pasos de trabajo (uno por linea)</Label>
                <textarea
                    id="work_steps_text"
                    v-model="form.work_steps_text"
                    rows="5"
                    required
                    class="flex min-h-[120px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                />
                <InputError :message="form.errors.work_steps_text" />
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold">Servicios</h2>
                    <Button type="button" variant="outline" size="sm" @click="addService">
                        Agregar servicio
                    </Button>
                </div>

                <div
                    v-for="(service, index) in form.services"
                    :key="index"
                    class="space-y-3 rounded-lg border border-sidebar-border/70 p-4"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium">Servicio {{ index + 1 }}</p>
                        <Button
                            v-if="form.services.length > 1"
                            type="button"
                            variant="ghost"
                            size="sm"
                            @click="removeService(index)"
                        >
                            Quitar
                        </Button>
                    </div>
                    <div class="grid gap-2">
                        <Label :for="`service-title-${index}`">Titulo</Label>
                        <Input :id="`service-title-${index}`" v-model="service.title" required />
                        <InputError :message="form.errors[`services.${index}.title`]" />
                    </div>
                    <div class="grid gap-2">
                        <Label :for="`service-description-${index}`">Descripcion</Label>
                        <textarea
                            :id="`service-description-${index}`"
                            v-model="service.description"
                            rows="3"
                            required
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        />
                        <InputError :message="form.errors[`services.${index}.description`]" />
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold">Educacion</h2>
                    <Button type="button" variant="outline" size="sm" @click="addEducation">
                        Agregar titulo
                    </Button>
                </div>

                <div
                    v-for="(item, index) in form.education"
                    :key="index"
                    class="space-y-3 rounded-lg border border-sidebar-border/70 p-4"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium">Titulo {{ index + 1 }}</p>
                        <Button
                            v-if="form.education.length > 1"
                            type="button"
                            variant="ghost"
                            size="sm"
                            @click="removeEducation(index)"
                        >
                            Quitar
                        </Button>
                    </div>
                    <div class="grid gap-2">
                        <Label :for="`education-institution-${index}`">Institucion</Label>
                        <Input
                            :id="`education-institution-${index}`"
                            v-model="item.institution"
                            required
                        />
                        <InputError :message="form.errors[`education.${index}.institution`]" />
                    </div>
                    <div class="grid gap-2">
                        <Label :for="`education-degree-${index}`">Grado</Label>
                        <Input :id="`education-degree-${index}`" v-model="item.degree" required />
                        <InputError :message="form.errors[`education.${index}.degree`]" />
                    </div>
                    <div class="grid gap-2">
                        <Label :for="`education-field-${index}`">Area</Label>
                        <Input :id="`education-field-${index}`" v-model="item.field" required />
                        <InputError :message="form.errors[`education.${index}.field`]" />
                    </div>
                </div>
            </div>

            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
            </Button>
        </form>
    </div>
</template>
