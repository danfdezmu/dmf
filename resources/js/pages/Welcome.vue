<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login } from '@/routes';

type Profile = {
    name: string;
    role: string;
    intro: string;
    linkedin: string;
    email: string;
    brand: string;
};

type Service = {
    title: string;
    description: string;
};

type Project = {
    id: number;
    title: string;
    description: string;
    stack: string;
    url: string | null;
};

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
    period: string;
    description: string | null;
    logo: string | null;
    initials: string;
    website_url: string | null;
    link_preview: LinkPreview | null;
};

type CompanyWebsite = {
    company: string;
    website_url: string;
    link_preview: LinkPreview | null;
    logo: string | null;
    initials: string;
};

type Education = {
    institution: string;
    degree: string;
    field: string;
};

defineProps<{
    profile: Profile;
    workSteps: string[];
    services: Service[];
    experiences: Experience[];
    companyWebsites: CompanyWebsite[];
    education: Education[];
    projects: Project[];
}>();
</script>

<template>
    <Head :title="`${profile.name} | Desarrollador`">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div
        class="min-h-screen bg-slate-50 font-[DM_Sans,ui-sans-serif,system-ui,sans-serif] text-slate-700 antialiased"
    >
        <header
            class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 px-6 py-3 backdrop-blur-md"
        >
            <div class="mx-auto flex max-w-[1100px] items-center justify-between gap-4">
                <span class="font-sans text-base font-bold text-blue-900">
                    {{ profile.brand }}
                </span>

                <nav class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-blue-300 hover:text-blue-700"
                    >
                        Panel
                    </Link>
                    <Link
                        v-else
                        :href="login()"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        Iniciar sesion
                    </Link>
                </nav>
            </div>
        </header>

        <section
            class="bg-gradient-to-br from-slate-900 via-blue-900 to-blue-700 px-6 py-16 text-center text-white md:py-20"
        >
            <div class="mx-auto max-w-[1100px]">
                <span
                    class="mb-5 inline-block rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider"
                >
                    Portafolio profesional
                </span>
                <h1 class="font-sans text-4xl font-bold leading-tight md:text-5xl">
                    {{ profile.name }}
                </h1>
                <p class="mt-3 text-lg font-medium text-blue-100">
                    {{ profile.role }}
                </p>
                <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-white/90">
                    {{ profile.intro }}
                </p>

                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a
                        :href="profile.linkedin"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-blue-900 shadow-md transition hover:bg-blue-50"
                    >
                        Ver LinkedIn
                    </a>
                    <a
                        :href="`mailto:${profile.email}`"
                        class="rounded-lg border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20"
                    >
                        Solicitar presupuesto
                    </a>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-[1100px] space-y-14 px-6 pb-20 pt-12">
            <section>
                <div class="mb-7 border-b-2 border-blue-100 pb-4">
                    <h2 class="font-sans text-2xl font-bold text-slate-900">Como trabajo</h2>
                    <p class="mt-1 text-sm text-slate-500">Proceso claro de principio a fin</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div
                        v-for="(step, index) in workSteps"
                        :key="index"
                        class="rounded-lg border border-slate-200 border-l-4 border-l-blue-600 bg-slate-50/80 p-4"
                    >
                        <strong class="font-sans text-slate-900">Paso {{ index + 1 }}</strong>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ step }}</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="mb-7 border-b-2 border-blue-100 pb-4">
                    <h2 class="font-sans text-2xl font-bold text-slate-900">Servicios</h2>
                    <p class="mt-1 text-sm text-slate-500">Soluciones que puedo ofrecerte</p>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    <article
                        v-for="service in services"
                        :key="service.title"
                        class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_4px_24px_rgba(15,23,42,0.08)]"
                    >
                        <h3 class="font-sans text-lg font-semibold text-blue-900">
                            {{ service.title }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            {{ service.description }}
                        </p>
                    </article>
                </div>
            </section>

            <section>
                <div class="mb-7 border-b-2 border-blue-100 pb-4">
                    <h2 class="font-sans text-2xl font-bold text-slate-900">Experiencia laboral</h2>
                    <p class="mt-1 text-sm text-slate-500">Trayectoria profesional</p>
                </div>

                <div v-if="experiences.length" class="space-y-4">
                    <article
                        v-for="experience in experiences"
                        :key="experience.id"
                        class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_4px_24px_rgba(15,23,42,0.08)]"
                    >
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex min-w-0 flex-1 gap-4">
                                <div
                                    class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-white p-2"
                                >
                                    <img
                                        v-if="experience.logo"
                                        :src="experience.logo"
                                        :alt="`Logo ${experience.company}`"
                                        class="max-h-full max-w-full object-contain"
                                    />
                                    <span
                                        v-else
                                        class="font-sans text-sm font-bold text-blue-900"
                                    >
                                        {{ experience.initials }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-sans text-lg font-semibold text-slate-900">
                                        {{ experience.role }}
                                    </h3>
                                    <p class="mt-1 text-base font-medium text-blue-900">
                                        {{ experience.company }}
                                    </p>
                                    <p
                                        v-if="experience.location"
                                        class="mt-1 text-sm text-slate-500"
                                    >
                                        {{ experience.location }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="shrink-0 rounded-lg bg-blue-50 px-3 py-1 text-sm font-medium text-blue-800"
                            >
                                {{ experience.period }}
                            </span>
                        </div>
                        <p
                            v-if="experience.description"
                            class="mt-4 text-sm leading-relaxed text-slate-600"
                        >
                            {{ experience.description }}
                        </p>
                        <a
                            v-if="experience.website_url"
                            :href="experience.website_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline"
                        >
                            Visitar sitio web
                            <span aria-hidden="true">↗</span>
                        </a>
                    </article>
                </div>
                <p v-else class="text-sm text-slate-500">
                    La experiencia laboral se mostrara aqui cuando la agregues desde el panel.
                </p>
            </section>

            <section v-if="companyWebsites.length">
                <div class="mb-7 border-b-2 border-blue-100 pb-4">
                    <h2 class="font-sans text-2xl font-bold text-slate-900">
                        Sitios de las organizaciones
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Vista previa de las paginas donde he trabajado
                    </p>
                </div>

                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <a
                        v-for="site in companyWebsites"
                        :key="site.website_url"
                        :href="site.website_url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_4px_24px_rgba(15,23,42,0.08)] transition hover:border-blue-300 hover:shadow-lg"
                    >
                        <div
                            class="relative aspect-[1.91/1] overflow-hidden bg-gradient-to-br from-blue-50 to-slate-100"
                        >
                            <img
                                v-if="site.link_preview?.image"
                                :src="site.link_preview.image"
                                :alt="site.link_preview.title"
                                class="size-full object-cover transition group-hover:scale-[1.02]"
                            />
                            <div
                                v-else
                                class="flex size-full flex-col items-center justify-center gap-2 p-4"
                            >
                                <img
                                    v-if="site.logo"
                                    :src="site.logo"
                                    :alt="site.company"
                                    class="size-12 object-contain"
                                />
                                <span
                                    v-else
                                    class="font-sans text-lg font-bold text-blue-900"
                                >
                                    {{ site.initials }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    {{ site.link_preview?.domain ?? site.website_url }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-blue-600">
                                {{ site.link_preview?.domain ?? site.website_url }}
                            </p>
                            <h3
                                class="mt-1 font-sans text-base font-semibold text-slate-900 group-hover:text-blue-800"
                            >
                                {{ site.link_preview?.title ?? site.company }}
                            </h3>
                            <p
                                v-if="site.link_preview?.description"
                                class="mt-2 line-clamp-2 text-sm text-slate-600"
                            >
                                {{ site.link_preview.description }}
                            </p>
                            <p class="mt-3 text-xs font-medium text-blue-600">
                                Abrir sitio ↗
                            </p>
                        </div>
                    </a>
                </div>
            </section>

            <section v-if="education.length">
                <div class="mb-7 border-b-2 border-blue-100 pb-4">
                    <h2 class="font-sans text-2xl font-bold text-slate-900">Educacion</h2>
                    <p class="mt-1 text-sm text-slate-500">Formacion academica</p>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <article
                        v-for="(item, index) in education"
                        :key="index"
                        class="rounded-xl border border-slate-200 bg-gradient-to-b from-blue-50 to-white p-6 shadow-[0_4px_24px_rgba(15,23,42,0.08)]"
                    >
                        <h3 class="font-sans text-lg font-semibold text-blue-900">
                            {{ item.institution }}
                        </h3>
                        <p class="mt-2 text-sm font-medium text-blue-600">
                            {{ item.degree }} · {{ item.field }}
                        </p>
                    </article>
                </div>
            </section>

            <section>
                <div class="mb-7 border-b-2 border-blue-100 pb-4">
                    <h2 class="font-sans text-2xl font-bold text-slate-900">Proyectos y demos</h2>
                    <p class="mt-1 text-sm text-slate-500">Trabajos recientes y MVPs</p>
                </div>

                <div v-if="projects.length" class="grid gap-5 md:grid-cols-3">
                    <article
                        v-for="project in projects"
                        :key="project.id"
                        class="flex flex-col rounded-xl border border-slate-200 bg-white p-6 shadow-[0_4px_24px_rgba(15,23,42,0.08)]"
                    >
                        <h3 class="font-sans text-lg font-semibold text-slate-900">
                            <a
                                v-if="project.url"
                                :href="project.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-blue-700 hover:text-blue-900 hover:underline"
                            >
                                {{ project.title }}
                            </a>
                            <span v-else>{{ project.title }}</span>
                        </h3>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">
                            {{ project.description }}
                        </p>
                        <p
                            class="mt-4 inline-block self-start rounded bg-blue-100 px-2 py-1 text-xs font-bold uppercase tracking-wide text-blue-900"
                        >
                            {{ project.stack }}
                        </p>
                    </article>
                </div>
                <p v-else class="text-sm text-slate-500">Proximamente nuevos proyectos.</p>
            </section>
        </main>

        <footer class="border-t border-slate-200 bg-white px-6 py-8 text-center text-sm text-slate-500">
            <p>{{ profile.name }} · {{ profile.role }}</p>
            <a
                :href="`mailto:${profile.email}`"
                class="mt-2 inline-block font-medium text-blue-600 hover:text-blue-800 hover:underline"
            >
                {{ profile.email }}
            </a>
        </footer>
    </div>
</template>
