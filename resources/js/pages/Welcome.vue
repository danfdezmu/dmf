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

type Experience = {
    id: number;
    company: string;
    role: string;
    location: string | null;
    period: string;
    description: string | null;
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
    education: Education[];
    projects: Project[];
}>();
</script>

<template>
    <Head :title="`${profile.name} | Desarrollador`" />

    <div class="min-h-screen bg-zinc-950 text-zinc-100">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-6">
            <span class="text-sm font-semibold tracking-wide text-zinc-300">{{ profile.brand }}</span>

            <nav class="flex items-center gap-3">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="rounded-md border border-zinc-700 px-4 py-2 text-sm text-zinc-100 transition hover:border-zinc-500"
                >
                    Panel
                </Link>
                <Link
                    v-else
                    :href="login()"
                    class="rounded-md border border-emerald-500/70 px-4 py-2 text-sm text-emerald-300 transition hover:border-emerald-400 hover:text-emerald-200"
                >
                    Iniciar sesion
                </Link>
            </nav>
        </header>

        <main class="mx-auto w-full max-w-6xl space-y-18 px-6 pb-16 pt-10">
            <section class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <div class="space-y-5">
                    <p class="text-sm uppercase tracking-[0.2em] text-zinc-400">Portafolio profesional</p>
                    <h1 class="text-4xl font-bold leading-tight md:text-5xl">
                        {{ profile.name }}
                    </h1>
                    <p class="text-lg text-emerald-300">{{ profile.role }}</p>
                    <p class="max-w-2xl text-base text-zinc-300">
                        {{ profile.intro }}
                    </p>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <a
                            :href="profile.linkedin"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-md bg-emerald-500 px-4 py-2 text-sm font-medium text-zinc-950 transition hover:bg-emerald-400"
                        >
                            Ver LinkedIn
                        </a>
                        <a
                            :href="`mailto:${profile.email}`"
                            class="rounded-md border border-zinc-700 px-4 py-2 text-sm text-zinc-100 transition hover:border-zinc-500"
                        >
                            Solicitar presupuesto
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/70 p-6">
                    <h2 class="text-lg font-semibold">Como trabajo</h2>
                    <ul class="mt-4 space-y-3 text-sm text-zinc-300">
                        <li v-for="(step, index) in workSteps" :key="index">
                            {{ index + 1 }}. {{ step }}
                        </li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-semibold">Servicios</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <article
                        v-for="service in services"
                        :key="service.title"
                        class="rounded-xl border border-zinc-800 bg-zinc-900/60 p-5"
                    >
                        <h3 class="text-base font-semibold text-zinc-100">{{ service.title }}</h3>
                        <p class="mt-2 text-sm text-zinc-300">{{ service.description }}</p>
                    </article>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-semibold">Experiencia laboral</h2>
                <div v-if="experiences.length" class="relative mt-8 space-y-0">
                    <div
                        class="absolute bottom-0 left-[7px] top-2 w-px bg-zinc-800"
                        aria-hidden="true"
                    />
                    <article
                        v-for="experience in experiences"
                        :key="experience.id"
                        class="relative grid gap-2 pb-10 pl-8 last:pb-0"
                    >
                        <span
                            class="absolute left-0 top-2 size-3.5 rounded-full border-2 border-emerald-500 bg-zinc-950"
                            aria-hidden="true"
                        />
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h3 class="text-lg font-semibold text-zinc-100">
                                {{ experience.role }}
                            </h3>
                            <span class="text-sm text-emerald-300/90">{{ experience.period }}</span>
                        </div>
                        <p class="text-base font-medium text-zinc-200">
                            {{ experience.company }}
                            <span
                                v-if="experience.location"
                                class="font-normal text-zinc-500"
                            >
                                · {{ experience.location }}
                            </span>
                        </p>
                        <p
                            v-if="experience.description"
                            class="max-w-3xl text-sm leading-relaxed text-zinc-400"
                        >
                            {{ experience.description }}
                        </p>
                    </article>
                </div>
                <p v-else class="mt-4 text-sm text-zinc-400">
                    La experiencia laboral se mostrara aqui cuando la agregues desde el panel.
                </p>
            </section>

            <section v-if="education.length">
                <h2 class="text-2xl font-semibold">Educacion</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <article
                        v-for="(item, index) in education"
                        :key="index"
                        class="rounded-xl border border-zinc-800 bg-zinc-900/60 p-5"
                    >
                        <h3 class="text-base font-semibold text-zinc-100">
                            {{ item.institution }}
                        </h3>
                        <p class="mt-2 text-sm text-emerald-300">
                            {{ item.degree }} · {{ item.field }}
                        </p>
                    </article>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-semibold">Proyectos y demos</h2>
                <div v-if="projects.length" class="mt-6 grid gap-4 md:grid-cols-3">
                    <article
                        v-for="project in projects"
                        :key="project.id"
                        class="rounded-xl border border-zinc-800 p-5"
                    >
                        <h3 class="text-base font-semibold">
                            <a
                                v-if="project.url"
                                :href="project.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hover:text-emerald-300"
                            >
                                {{ project.title }}
                            </a>
                            <span v-else>{{ project.title }}</span>
                        </h3>
                        <p class="mt-2 text-sm text-zinc-300">{{ project.description }}</p>
                        <p class="mt-4 text-xs uppercase tracking-wider text-emerald-300">
                            {{ project.stack }}
                        </p>
                    </article>
                </div>
                <p v-else class="mt-4 text-sm text-zinc-400">Proximamente nuevos proyectos.</p>
            </section>
        </main>
    </div>
</template>
