import preset from '../../../../vendor/filament/filament/tailwind.config.preset'
/** @type {import('tailwindcss').Config} */
export default {
    theme: {
        extend: {
            /*colors: {
                primary: '#2563eb',    // azul (aprox Bootstrap primary)
                secondary: '#6b7280',  // gris medio (secondary)
                success: '#16a34a',    // verde (success)
                danger: '#dc2626',     // rojo (danger)
                warning: '#fbbf24',    // amarillo (warning)
                info: '#0ea5e9',       // azul claro (info)
                light: '#f3f4f6',      // gris claro (light)
                dark: '#111827',       // gris oscuro (dark)
            },*/
            colors: {
                primary: '#2563eb',
                secondary: '#6b7280',
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
                warning: {
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                },
                info: {
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                },
                light: '#f3f4f6',
                dark: '#111827',
            },
        },
    },
    presets: [preset],
    // content: [
    //     './app/Filament/**/*.php',
    //     './resources/views/filament/**/*.blade.php',
    //     './vendor/filament/**/*.blade.php',
    // ],
    content: [
        './app/**/*.php',                         // Todos los archivos PHP
        './resources/**/*.blade.php',            // Todas las vistas Blade
        './resources/**/*.js',                   // JS o Alpine.js
        './resources/**/*.vue',                  // Si usas Vue
        './vendor/filament/**/*.blade.php',      // Vistas de Filament
    ],

    safelist: [
        'justify-self-start',
        'justify-self-end',
        'justify-self-center',
        'md:justify-self-start',
        'md:justify-self-end',
        'md:justify-self-center',
        'w-auto',
        'md:w-auto',
        'text-xs', 'text-sm',
        'sm:text-xs', 'sm:text-sm',
        'md:text-xs', 'md:text-sm',
        'lg:text-xs', 'lg:text-sm',
        'xl:text-xs', 'xl:text-sm',

        'grid', // Habilita display: grid
        'inline-grid', // Habilita display: inline-grid
        'grid-cols-1', 'grid-cols-2', 'grid-cols-3', 'grid-cols-4', 'grid-cols-5',
        'col-span-1', 'col-span-2', 'col-span-3', 'col-span-4', 'col-span-5',
        'sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4',
        'sm:col-span-1', 'md:col-span-2', 'lg:col-span-3', 'xl:col-span-4',
        'gap-1', 'gap-2', 'gap-4', 'gap-6', 'gap-8', 'gap-10',
        'p-2', 'p-4', 'p-6', 'm-2', 'm-4', 'm-6',
        'px-2', 'px-4', 'px-6', 'xm-2', 'xm-4', 'xm-6',
        'min-w-full', 'w-full',
        'mt-5',
        'mr-5',
        'mb-3',
        'mr-2',
        'text-2xl',

        // Colores
        'text-red-600',
        'text-red-500',
        'hover:text-red-600',
        'bg-violet-500',
        'bg-violet-700',
        'bg-fuchsia-500',
        'bg-fuchsia-700',
        'bg-blue-500',
        'bg-blue-700',
        'border-blue-700',
        'bg-yellow-400',
        'border-yellow-600',
        'bg-green-500',
        'hover:bg-green-600',
        'border-2',
        'border-green-500',
        'hover:border-green-600',
        'text-white',
        'hover:text-green-200',

        // Efectos de hover y transición
        'transform',
        'hover:scale-105',
        'shadow-md',

        // Otros posibles estilos
        'focus:outline-none',
        'transition-all',
        'duration-300',
        'ease-in-out',
        'border-green-500',
        'hover:border-green-300',
        'text-green-500',          // Color base del ícono
        'hover:text-green-300',    // Color al pasar el cursor
        // Clases específicas (útiles si el patrón no los detecta en tiempo de build)
        // Grid column counts (1–12)
        'grid-cols-1', // 1 columna
        'grid-cols-2', // 2 columnas
        'grid-cols-3', // 3 columnas
        'grid-cols-4', // 4 columnas
        'grid-cols-5', // 5 columnas
        'grid-cols-6', // 6 columnas
        'grid-cols-7', // 7 columnas
        'grid-cols-8', // 8 columnas
        'grid-cols-9', // 9 columnas
        'grid-cols-10', // 10 columnas
        'grid-cols-11', // 11 columnas
        'grid-cols-12', // 12 columnas

        // Column span (1–12)
        'col-span-1', // Ocupar 1 columna
        'col-span-2',
        'col-span-3',
        'col-span-4',
        'col-span-5',
        'col-span-6',
        'col-span-7',
        'col-span-8',
        'col-span-9',
        'col-span-10',
        'col-span-11',
        'col-span-12',

        // Responsive: grid-cols (1–12)
        'sm:grid-cols-1', 'sm:grid-cols-2', 'sm:grid-cols-3', 'sm:grid-cols-4',
        'sm:grid-cols-5', 'sm:grid-cols-6', 'sm:grid-cols-7', 'sm:grid-cols-8',
        'sm:grid-cols-9', 'sm:grid-cols-10', 'sm:grid-cols-11', 'sm:grid-cols-12',

        'md:grid-cols-1', 'md:grid-cols-2', 'md:grid-cols-3', 'md:grid-cols-4',
        'md:grid-cols-5', 'md:grid-cols-6', 'md:grid-cols-7', 'md:grid-cols-8',
        'md:grid-cols-9', 'md:grid-cols-10', 'md:grid-cols-11', 'md:grid-cols-12',

        'lg:grid-cols-1', 'lg:grid-cols-2', 'lg:grid-cols-3', 'lg:grid-cols-4',
        'lg:grid-cols-5', 'lg:grid-cols-6', 'lg:grid-cols-7', 'lg:grid-cols-8',
        'lg:grid-cols-9', 'lg:grid-cols-10', 'lg:grid-cols-11', 'lg:grid-cols-12',

        'xl:grid-cols-1', 'xl:grid-cols-2', 'xl:grid-cols-3', 'xl:grid-cols-4',
        'xl:grid-cols-5', 'xl:grid-cols-6', 'xl:grid-cols-7', 'xl:grid-cols-8',
        'xl:grid-cols-9', 'xl:grid-cols-10', 'xl:grid-cols-11', 'xl:grid-cols-12',

        // Responsive: col-span (1–12)
        'sm:col-span-1', 'sm:col-span-2', 'sm:col-span-3', 'sm:col-span-4',
        'sm:col-span-5', 'sm:col-span-6', 'sm:col-span-7', 'sm:col-span-8',
        'sm:col-span-9', 'sm:col-span-10', 'sm:col-span-11', 'sm:col-span-12',

        'md:col-span-1', 'md:col-span-2', 'md:col-span-3', 'md:col-span-4',
        'md:col-span-5', 'md:col-span-6', 'md:col-span-7', 'md:col-span-8',
        'md:col-span-9', 'md:col-span-10', 'md:col-span-11', 'md:col-span-12',

        'lg:col-span-1', 'lg:col-span-2', 'lg:col-span-3', 'lg:col-span-4',
        'lg:col-span-5', 'lg:col-span-6', 'lg:col-span-7', 'lg:col-span-8',
        'lg:col-span-9', 'lg:col-span-10', 'lg:col-span-11', 'lg:col-span-12',

        'xl:col-span-1', 'xl:col-span-2', 'xl:col-span-3', 'xl:col-span-4',
        'xl:col-span-5', 'xl:col-span-6', 'xl:col-span-7', 'xl:col-span-8',
        'xl:col-span-9', 'xl:col-span-10', 'xl:col-span-11', 'xl:col-span-12',


    ]



}
