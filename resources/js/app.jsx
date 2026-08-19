import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';

// KITA IMPORT LANGSUNG SECARA MANUAl
import Home from './Pages/Home.jsx';

createInertiaApp({
    title: (title) => title ? `${title} - Novelia` : 'Novelia',
    resolve: (name) => {
        // Kita paksa: apa pun halaman yang diminta Laravel, serahkan komponen Home!
        return Home;
    },
    setup({ el, App, props }) {
        // Log darurat paling atas untuk memastikan fungsi setup React berjalan
        console.log("=== REAKSI ROOT SEDANG BERJALAN ===");
        
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
});